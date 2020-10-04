<?php
/**
 * Created by PhpStorm.
 * User: nikita
 * Date: 29/3/18
 * Time: 10:59 AM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\StaticPages;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AdminStaticPagesController extends Controller
{
    private $validationRules = [
        'description' => 'required',
        'page_image' => 'mimes:jpeg,jpg,png',

    ];

    /**
     * Display Blog details.
     *
     * @return json
     */
    public function index()
    {
        return view('admin.staticPages.index');
    }

    /**
     * Search Blog.
     *
     * @return json
     */
    public function search(Request $request)
    {

        if ($request->ajax())
        {
            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage)
            {
                return $currentPage;
            });

            $query = StaticPages::selectRaw("page_name,headline,id,status");
            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);
            $this->filterBlog($request->search['value'], $query);

            $page = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length);

            $data = json_decode(json_encode($page));
            $data->recordsFiltered = $data->recordsTotal = $data->total;

            foreach ($data->data as $pages)
            {
                $pages->action = '<a href="' . url(route('pages.edit', ['pages' => $pages->id])) . '" title="Edit"><i class="la la-edit"></i></a>';

            }

            return response()->json($data);
        }
    }

    /**
     * Filter Blog listing.
     *
     * @param $search
     * @return $query
     */
    private function filterBlog($search, $query)
    {
        $query->where('page_name', 'like', '%' . $search . '%')
            ->orWhere('headline', 'like', '%' . $search . '%');
    }

    /**
     * Display create blog page.
     *
     * @return json
     */
    public function create()
    {
        return view('admin.staticPages.create');
    }

    /**
     * Save the Blog.
     * @param Request $request
     * @return json
     */
    public function store(Request $request)
    {
        //dd($request->all());die;
        $this->validate($request, $this->validationRules);
        $pages = new StaticPages();
        $pages->fill($request->all());

        if ($pages->save())
        {

            $pages->slug = str_slug($pages->page_name);
            $pages->save();
            return redirect(route('pages.index'))->with('success', trans('messages.page.added'));
        }

        return redirect(route('pages.index'))->with('error', trans('messages.error'));
    }

    /**
     * Change status of the blog.
     *
     * @param Customer $user
     * @return json
     */
    public function changeStatus(StaticPages $page)
    {
        $page->status = ! $page->status;

        if ($page->save())
        {

            return redirect(route('pages.index'))->with('success', trans('messages.page.change_status'));
        }

        return redirect(route('pages.index'))->with('error', trans('messages.error'));
    }

    /**
     * Show Blog edit page.
     *
     * @param Blog $blog
     * @return json
     */
    public function edit(StaticPages $page)
    {
        return view('admin.staticPages.create', [
            'page' => $page,
        ]);
    }

    /**
     * Update the static page.
     *
     * @param Request $request
     * @param int $page
     * @return json
     */
    public function update(Request $request, StaticPages $page)
    {
        $this->validate($request, $this->validationRules);

        $page->fill($request->all());
        $image = $request->file('page_image');
        $destinationPath = base_path('doc/page_image');

        $page->slug = str_slug($page->page_name);
        if ($page->save())
        {

            return redirect(route('pages.edit',['page'=>$page->id]))->with('success', trans('messages.page.updated'));
        }

        return redirect(route('pages.edit',['page'=>$page->id]))->with('error', trans('messages.error'));
    }

    /**
     * Delete static page by unique identifier.
     *
     * @return json
     */
    public function destroy(StaticPages $page)
    {
        if ($page->delete())
        {

            return redirect(route('pages.index'))->with('success', trans('messages.page.deleted'));
        }

        return redirect(route('pages.index'))->with('error', trans('messages.error'));
    }
}