<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Base\Product\Dto\GetPriceFilter;
use App\Base\Product\Manager as ProductManager;
use App\Base\System\Events\SystemLevelErrorOccurred;
use App\Exceptions\RateLimitedException;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class PriceController extends Controller
{
    /**
     * PriceController constructor.
     *
     * @param ProductManager $product_manager
     */
    public function __construct(
        private readonly ProductManager $product_manager,
    ) {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/prices",
     *     summary="Получить список товаров с ценами",
     *     description="Возвращает список товаров с ценами в заданной валюте (RUB, USD, EUR). По умолчанию — RUB.",
     *     operationId="getPrices",
     *     tags={"Products"},
     *
     *     @OA\Parameter(
     *         name="currency",
     *         in="query",
     *         description="Валюта отображения цен. Поддерживаемые значения: rub, usd, eur.",
     *         required=false,
     *         @OA\Schema(type="string", enum={"rub", "usd", "eur"}, default="rub")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Номер страницы для пагинации (начиная с 1)",
     *         required=false,
     *         @OA\Schema(type="integer", example=1, minimum=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Количество элементов на странице",
     *         required=false,
     *         @OA\Schema(type="integer", example=10, minimum=1, maximum=100)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ со списком товаров",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=50)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации параметров",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Произошла ошибка при валидации"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Превышен лимит запросов",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Слишком много запросов, попробуйте позже")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Критическая ошибка сервера",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Сервис временно недоступен.")
     *         )
     *     )
     * )
     */
    public function __invoke(Request $request)
    {
        $rate_limit_key = "get:prices:".$request->ip();

        try {
            if (RateLimiter::tooManyAttempts($rate_limit_key, 5)) {
                throw new RateLimitedException("Слишком много запросов, попробуйте позже", ResponseAlias::HTTP_TOO_MANY_REQUESTS);
            }

            RateLimiter::hit($rate_limit_key, 1);

            $products = $this->product_manager->getAllConverted(GetPriceFilter::validateAndCreate([
                'currency' => $request->get('currency', 'rub'),
                'pagination' => [
                    'page' => $request->get('page', 1),
                    'per_page' => $request->get('per_page', 10),
                ],
            ]));

            $result = ProductResource::collection($products);

            return response()->json([
                'data' => $result,
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ]);
        } catch (RateLimitedException $e) {
            my_logger()->file('warning_products')->warning("Превышен лимит запросов: IP {$request->ip()}");

            return response()->json(['message' => $e->getMessage()], $e->getCode());
        } catch (ValidationException $e) {
            my_logger()->file('warning_products')->warning("Ошибка валидации параметров: IP {$request->ip()}", [
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'message' => 'Ошибка валидации параметров',
                'errors' => $e->errors(),
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
        catch (Throwable $e) {
            my_logger()->file('error_products')->error("Системная ошибка: IP {$request->ip()}, сообития [".SystemLevelErrorOccurred::class."] будет отправлено.", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Сервис временно недоступен.',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
