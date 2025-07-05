<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Base\Product\Dto\GetPriceFilter;
use App\Base\Product\Manager as ProductManager;
use App\Exceptions\RateLimitedException;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;
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
     *             @OA\Property(property="message", type="string", example="Критическая ошибка на стороне сервера, обратитесь к администратору")
     *         )
     *     )
     * )
     */
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $rate_limit_key = "get:prices:".$request->ip();

        try {
            if (RateLimiter::tooManyAttempts($rate_limit_key, 5)) {
                throw new RateLimitedException("Слишком много запросов, попробуйте позже", 429);
            }

            RateLimiter::hit($rate_limit_key, 1);

            $products = $this->product_manager->getAllConverted(GetPriceFilter::validateAndCreate([
                'currency' => $request->get('currency', 'rub'),
                'pagination' => [
                    'page' => $request->get('page', 1),
                    'per_page' => $request->get('per_page', 10),
                ],
            ]));

            return ProductResource::collection($products);
        } catch (RateLimitedException $e) {
            logger()->warning("Превышен лимит запросов: IP {$request->ip()}");

            return response()->json(['message' => $e->getMessage()], $e->getCode());
        } catch (ValidationException $e) {
            logger()->error("Произошла ошибка при валидации: IP {$request->ip()}", [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Произошла ошибка при валидации',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            logger()->error("Критическая ошибка на стороне сервера: IP {$request->ip()}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Критическая ошибка на стороне сервера, обратитесь к администратору',
            ], 500);
        }
    }
}
