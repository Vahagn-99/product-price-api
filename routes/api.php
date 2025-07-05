<?php

foreach (File::allFiles(__DIR__.'/api') as $file) {
    require $file;
}