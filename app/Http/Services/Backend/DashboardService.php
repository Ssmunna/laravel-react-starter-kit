<?php
namespace App\Http\Services\Backend;
use App\Models\Contact;
use App\Models\Visitor;
use App\Traits\Response;

class DashboardService
{
    use Response;

    /**
     * @param array $query
     * @return array
     */
    public function Home (array $query): array
    {
        try {
            $uniqueVisitor = Visitor::all()->count();
            $totalContact  = Contact::all()->count();

           return $this->response([
               'unique_visitor' => $uniqueVisitor,
               'total_contact' => $totalContact,
           ])->success();
        } catch (\Exception $exception) {
            return $this->response( )->error($exception->getMessage());
        }
    }
}
