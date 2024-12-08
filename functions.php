<?php

function client_index_func()
{
    $service = new \App\Services\ProductService();

    $latestProducts = $service->latest()->paginate(10);
    $bestSellingProducts = $service->addScope('variant_items')->bestSelling()->paginate(10);

    // Share data
    \View::share('latestProducts', $latestProducts);
    \View::share('bestSellingProducts', $bestSellingProducts);
}
