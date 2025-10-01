<?php
namespace App\Http\Services\Backend;

use App\Models\BlogSection;
use App\Traits\FileSaver;
use App\Traits\Request;
use App\Traits\Response;
use Bitsmind\GraphSql\Facades\QueryAssist;
use Bitsmind\GraphSql\QueryAssist as QueryAssistTrait;
use Illuminate\Support\Str;

class BlogSectionService
{
    use Request,Response, QueryAssistTrait, FileSaver;

    /**
     * @param array $query
     * @param string $pageName
     * @return array
     */
    public function getListData (array $query, string $pageName): array
    {
        try {
            $validationErrorMsg = $this->queryParams($query)->required([]);
            if ($validationErrorMsg) {
                return $this->response()->error($validationErrorMsg);
            }

            if (!array_key_exists('graph', $query)) {
                $query['graph'] = '{*}';
            }

            if(!array_key_exists('page_name', $query)) {
                $query['page_name'] = $pageName;
            }

            $dbQuery = BlogSection::query();
            $dbQuery = QueryAssist::queryOrderBy($dbQuery, $query);
            $dbQuery = QueryAssist::queryWhere($dbQuery, $query, ['status','page_name']);
            $dbQuery = QueryAssist::queryGraphSQL($dbQuery, $query, new BlogSection);

            if (array_key_exists('search', $query)) {
                $dbQuery = $dbQuery->where('title', 'like', '%'.$query['search'].'%');
            }

            $count = $dbQuery->count();
            $blogs = $this->queryPagination($dbQuery, $query)->get();

            return $this->response([
                'blogs' => $blogs,
                'count' => $count,
                'blogStatus' => commonStatus(),
                ...$query
            ])->success();
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @param string $pageName
     * @return array
     */
    public function storeData (array $payload, string $pageName): array
    {
        try {
            $imageName = $this->upload_file( $payload['image'], $pageName,'blog');

            if(!array_key_exists('page_name', $payload)) {
                $payload['page_name'] = $pageName;
            }

            BlogSection::create( $this->_formatedBlogCreatedData( $payload, $imageName));

            return $this->response()->success('Blog created successfully');

        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @param string $pageName
     * @return array
     */
    public function updateData (array $payload, string $pageName): array
    {
        try {
            $blog = BlogSection::where('id', $payload['id'])->where('page_name', $pageName)->first();
            if(!$blog) {
                return $this->response()->error('Blog not found');
            }

            $imageName = null;
            if($payload['image']){
                $imageName = $this->upload_file( $payload['image'], 'tour', 'blog', $blog->image);
            }

            if(!array_key_exists('page_name', $payload)) {
                $payload['page_name'] = $pageName;
            }

            $blog->update( $this->_formatedBlogUpdatedData( $payload, $imageName));

            return $this->response()->success('Blog updated successfully');

        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @param string $pageName
     * @return array
     */
    public function changeBlogStatus (array $payload, string $pageName): array
    {
        try {
            $blog = BlogSection::where('id', $payload['id'])->where('page_name', $pageName)->first();
            if (!$blog) {
                return $this->response()->error("Blog not found");
            }

            $blog->update(['status' => $payload['status']]);

            return $this->response(['blog' => $blog])->success('Blog Status Updated Successfully');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }

    /**
     * @param string $id
     * @param string $pageName
     * @return array
     */
    public function deleteBlog (string $id, string $pageName): array
    {
        try {
            $blog = BlogSection::where('id', $id)->where('page_name', $pageName)->first();
            if (!$blog) {
                return $this->response()->error("Blog not found");
            }

            if(!empty($blog->image)){
                unlink(public_path($blog->image));
            }

            $blog->delete();

            return $this->response()->success('Blog Deleted Successfully');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }

    /**
     * @param array $payload
     * @param string $imageName
     * @return array
     */
    private function _formatedBlogCreatedData(array $payload, string $imageName): array
    {
        $data = [
            'description' => $payload['description'],
            'image' => $imageName,
            'page_name' => $payload['page_name'],
        ];

        if(!empty($payload['title'])) $data['title'] = $payload['title'];

        return $data;
    }


    /**
     * @param array $payload
     * @param string|null $imageName
     * @return array
     */
    private function _formatedBlogUpdatedData(array $payload, string $imageName = null): array
    {
        $data = [];

        if(!empty($payload['title']))               $data['title']          = $payload['title'];
        if(!empty($payload['description']))         $data['description']    = $payload['description'];
        if($imageName)                              $data['image']          = $imageName;

        return $data;
    }
}
