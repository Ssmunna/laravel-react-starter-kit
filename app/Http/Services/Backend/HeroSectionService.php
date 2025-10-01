<?php
namespace App\Http\Services\Backend;

use App\Models\HeroSection;
use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;


class HeroSectionService
{
    use Request,Response, QueryAssistTrait, FileSaver;

    /**
     * @param array $query
     * @return array
     */
    public function getData (array $query): array
    {
        try {
            $validationErrorMsg = $this->queryParams($query)->required([]);
            if ($validationErrorMsg) {
                return $this->response()->error($validationErrorMsg);
            }

            if (!array_key_exists('graph', $query)) {
                $query['graph'] = '{*}';
             }

            $dbQuery = HeroSection::query();
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['page_name']);
            $hero_section = $dbQuery->first();

            return $this->response(['hero_section' => $hero_section])->success();
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    public function updateData (array $payload): array
    {
        try {
            $heroSection = HeroSection::where('page_name', $payload['page_name'])->first();

            $imageName = null;
            if(!$heroSection) {
                if(!empty($payload['image'])){
                    $imageName = $this->upload_file( $payload['image'], 'home', 'hero-section');
                }
                HeroSection::create( $this->_formatedHeroSectionCreatedData( $payload, $imageName));
            }
            else{
                if(!empty($payload['image'])){
                    $imageName = $this->upload_file( $payload['image'], 'home', 'hero-section', $heroSection->image);
                }
                $heroSection->update( $this->_formatedHeroSectionUpdatedData( $payload, $imageName));
            }

            return $this->response()->success('HeroSection updated successfully');

        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }

    /**
     * @param array $payload
     * @param string $imageName
     * @return array
     */
    private function _formatedHeroSectionCreatedData(array $payload, string $imageName): array
    {
        $data = [
            'title' => $payload['title'],
            'page_name' => $payload['page_name'],
            'image' => $imageName,
        ];

        if(!empty($payload['description']) && array_key_exists('description', $payload))  $data['description'] = $payload['description'];

        return $data;
    }


    /**
     * @param array $payload
     * @param string|null $imageName
     * @return array
     */
    private function _formatedHeroSectionUpdatedData(array $payload, string $imageName = null): array
    {
        $data = [];

        if(array_key_exists('title', $payload) && !empty($payload['title']))                $data['title']          = $payload['title'];
        if(array_key_exists('description', $payload) && !empty($payload['description']))    $data['description']    = $payload['description'];
        if($imageName)                                                                           $data['image']          = $imageName;

        return $data;
    }
}
