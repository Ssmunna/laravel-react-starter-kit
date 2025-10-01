<?php
namespace App\Http\Services\Frontend;

use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;

class HomeService
{
    use Request,Response, QueryAssistTrait, FileSaver;

    /**
     * @param array $query
     * @return array
     */
    public function getData (array $query): array
    {
        try {
            return $this->response()->success();
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }

}
