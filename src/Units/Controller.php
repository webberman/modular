<?php

namespace Webberman\Modular\Units;

use Webberman\Modular\Bus\ServesFeatures;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Base controller.
 */
class Controller extends BaseController
{
    use ValidatesRequests;
    use ServesFeatures;
}
