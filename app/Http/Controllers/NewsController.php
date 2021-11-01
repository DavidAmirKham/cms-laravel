<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;

class NewsController extends Controller
{
    private $apiUrl;

    public function __construct() {
        $this->apiUrl = 'http://localhost:8080/api/';
    }

    public function home() {
        $weatherData = Http::get('https://api.openweathermap.org/data/2.5/weather?lang=ja&q=tokyo&appid=b38300b54e5a08a715c4c79c04c049c4');
        $response = Http::get($this->apiUrl . 'getLast10');
        $newsData = $this->paginate(json_decode($response));
        return view('home')->with([
            'weather_data' => json_decode($weatherData, true),
            'news_data' => $newsData,
        ]);
    }

    public function login(Request $request) {
        if(!empty(Session::get('id_or_mail')) && $request->path() == 'login') {
            return redirect('news');
        }
        return view('login');
    }

    public function logout() {
        Session::forget('id_or_mail');
        return redirect('home');
    }

    public function authenticate(Request $request) {
        $inputs = $request->all();
        $rules = [
            'id_or_mail' => 'required',
            'password' => 'required',
        ];
        $messages = [
            'id_or_mail.required' => 'ID/Mail address field is required',
            'password.required' => 'Password field is required',
        ];
        $validator = Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {
            return back()
            ->withInput()
            ->with(['errors' => $validator->errors()]);
        }
        if (($request['id_or_mail'] == 'admin' || $request['id_or_mail'] == 'admin@gmail.com') && $request['password'] == 'P@ssword1234') {
            Session::put('id_or_mail', 'admin');
            return redirect('/news_list');
        }
        return back()->withErrors(['fail' => "ID/Mail address and password doesn't match"]);
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
            ->withInput($request->input())
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
            return back()->withInput()->with(['fail' => 'Failure!']);
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
            return back()->with(['errors' => $validator->errors()]);
        }
        $response = Http::post($this->apiUrl . 'updateNews', [
            'id' => $id,
            'publish_from' => $request['publish_from'],
            'publish_to' => $request['publish_to'],
            'news' => $request['news'],
            'status' => $request['status'],
        ]);
        if ($response == 'success') {
            $response = Http::get($this->apiUrl . 'getMaxId');
            return view('news_add', ['id' => $response, 'success' => 'Update successfully!']);
        } else {
            return back()->withInput()->with(['fail' => 'Failure!']);
        }
    }

    public function news_delete($id, $search_text=null) {
        $response = Http::get($this->apiUrl . 'deleteNews/' . $id);
        return redirect('search_news?search_text=' . $search_text);
    }
}
