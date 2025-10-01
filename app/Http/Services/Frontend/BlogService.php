<?php
namespace App\Http\Services\Frontend;

use App\Models\BlogSection;
use App\Models\HeroSection;
use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;

class BlogService
{
    use Request,Response, QueryAssistTrait, FileSaver;

    /**
     * @param array $query
     * @return array
     */
    public function getListData (array $query): array
    {
        try {
            $validationErrorMsg = $this->queryParams($query)->required([]);
            if ($validationErrorMsg) {
                return $this->response()->error($validationErrorMsg);
            }

            if (!array_key_exists('graph', $query)) {
                $query['graph'] = '{image,title,description}';
                $query['status'] = STATUS_ACTIVE;
            }

            $dbQuery = BlogSection::query();
            $dbQuery = QueryAssist::queryOrderBy($dbQuery, $query);
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['status', 'page_name']);
            $dbQuery = QueryAssist::queryGraphSQL($dbQuery, $query, new BlogSection);
            $blogs   = $dbQuery->get();

            $heroSection = HeroSection::where('page_name', $query['page_name'])->select('title', 'image', 'description')->first();

            $query['page_name'] = 'about-me-bio';
            $dbQuery = BlogSection::query();
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['page_name']);
            $bio_section = $dbQuery->first();

            return $this->response([
                'blogs' => $blogs,
                'hero_section' => $heroSection,
                'bio_section' => $bio_section,
                ...$query
            ])->success();
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }

}
