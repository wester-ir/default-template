<?php

use App\Core\Client\ProductCore;

function client_index_func()
{
    $latestProducts = ProductCore::paginateLatestProducts(10);
    $bestSellingProducts = ProductCore::paginateBestSellingProducts(10);

    // Share data
    \View::share('latestProducts', $latestProducts);
    \View::share('bestSellingProducts', $bestSellingProducts);
}
