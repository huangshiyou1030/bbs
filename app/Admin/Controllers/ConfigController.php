<?php

namespace App\Admin\Controllers;

use App\Models\Config;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;


class ConfigController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {

        return $content
            ->header('配置列表')
            ->body(view('admin.config.index'));
    }
    public function update(Request $request,Config $config)
    {
        /*
         *
         * */
        $data = $request->only(
            'index_site_name', 'index_seo_keyword','index_seo_description',
           'contact_email'
            );
        $config->store($data);
        return redirect()->route('admin.config.index')->with('message', '修改成功');
    }
}
