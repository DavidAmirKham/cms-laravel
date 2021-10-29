<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsController extends Controller
{
    private $apiUrl;

    public function __construct() {
        $this->apiUrl = 'http://localhost:8080/api/';
    }

    public function news_add() {
        $response = Http::get($this->apiUrl . 'getMaxId');
        return view('news_add', ['id' => $response]);
    }

    public function news_insert(Request $request) {
        $inputs = $request->all();
        $rules = [
            'publish_from' => 'required',
            'publish_to' => 'required|date|after_or_equal:publish_from',
            'news' => 'required',
        ];
        $messages = [
            'publish_from.required' => 'PUBLISH FROM field is required',
            'publish_to.required' => 'PUBLISH TO field is required',
            'after_or_equal' => 'PUBLISH TO must be a date after or equal to PUBLISH FROM',
            'news.required' => 'NEWS field is required',
        ];
        $validator = Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {
            return back()
            ->withInput()
            ->with(['errors' => $validator->errors()]);
        }
        $response = Http::post($this->apiUrl . 'insertNews', [
            'id' => $request['id'],
            'publish_from' => $request['publish_from'],
            'publish_to' => $request['publish_to'],
            'news' => $request['news'],
            'status' => $request['status'],
        ]);
        if ($response == 'success') {
            $response = Http::get($this->apiUrl . 'getMaxId');
            return view('news_add', ['id' => $response, 'success' => 'Save successfully!']);
        } else {
            return view('news_add', ['fail' => 'Failure!']);
        }
    }

    public function search_news(Request $request) {
        $response = Http::get($this->apiUrl . 'searchNews/' . $request->Input('search_text'));
        $data = $this->paginate(json_decode($response));
        return view('news_list', ['data' => $data, 'search_text' => $request->Input('search_text')]);
    }

    public function paginate($items, $perPage = 20, $page = null) {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath()
        ]);
    }

    public function news_edit($id) {
        $response = Http::get($this->apiUrl . 'editNews/' . $id);
        return view('news_add', ['data' => json_decode($response)]);
    }

    public function news_update(Request $request, $id) {
        $inputs = $request->all();
        $rules = [
            'publish_from' => 'required',
            'publish_to' => 'required',
            'news' => 'required',
        ];
        $messages = [
            'publish_from.required' => 'PUBLISH FROM field is required',
            'publish_to.required' => 'PUBLISH TO field is required',
            'news.required' => 'NEWS field is required',
        ];
        $validator = Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {
            return back()
            ->withInput()
            ->with(['errors' => $validator->errors()]);
        }
        $response = Http::post($this->apiUrl . 'updateNews', [
            'id' => $id,
            'publish_from' => $request['publish_from'],
            'publish_to' => $request['publish_to'],
            'news' => $request['news'],
            'status' => $request['status'],
        ]);
        if ($response == 'success') {
            return view('news_add', ['success' => 'Update successfully!']);
        } else {
            return view('news_add', ['fail' => 'Failure!']);
        }
    }

    public function news_delete($id, $search_text=null) {
        $response = Http::get($this->apiUrl . 'deleteNews/' . $id);
        return redirect('search_news?search_text=' . $search_text);
    }
}
