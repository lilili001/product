<?php

namespace Modules\Product\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use Modules\Media\Events\FileWasUploaded;
use Modules\Media\Services\FileService;

use Modules\Media\Transformers\MediaTransformer;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Repositories\SkuRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AjaxResponse;
class BulkController extends AdminBaseController
{
    /**
     * @var SkuRepository
     */
    private $product;
    protected $fileService;

    public function __construct( FileService $fileService, ProductRepository $product)
    {
        parent::__construct();
        $this->product = $product;
        $this->fileService = $fileService;
    }

    public function import(Request $request)
    {

        if( $request->hasFile( 'file' ) ){
            $file = $request->file('file');
            $realPath = $file->getRealPath();
            $entension =  $file -> getClientOriginalExtension(); //上传文件的后缀.
            $tabl_name = date('YmdHis').mt_rand(100,999);
            $newName = $tabl_name.'.'.'xls';//$entension;
            $path = $file->move(base_path().'/uploads',$newName);
            $cretae_path = base_path().'/uploads/'.$newName;

            $data = Excel::load($cretae_path)->get()->toArray();

            $newArr = [];

            try{
                foreach ( $data as $key=>$row ){
                    foreach( $row as $attr=>$val ){
                        if( in_array($attr,['en','zh','sku_data']) ){
                            $newArr[$key][$attr] = json_decode($val,true);
                        }else if($attr == 'images'){
                            /* 上传图片到public/assets/media 下 */
                            $images = array_filter( explode(';',$val) ) ;

                            $orders = [];
                            foreach ( $images as $imgPath ){
                                $file_id = $this->getUploadedFileId($imgPath);
                                $newArr[$key]['medias_multi']['gallery']['files'][]  = $file_id;
                                $orders[] = $file_id;
                            }
                            $newArr[$key]['medias_multi']['gallery']['orders'] = implode(',',$orders);
                        }
                        else if($attr !== 'medias_multi'){
                            $newArr[$key][$attr] = $val;
                        }
                    }
                }

                /*插入到数据库*/
                foreach ( $newArr as $data_to_insert ) {
                    $this->product->create($data_to_insert);
                }

            }catch (Exception $e){
                return AjaxResponse::fail($e->getMessage());
            }

            return AjaxResponse::success('成功');
        }
    }

    public function getUploadedFileId($path)
    {
        $file = new UploadedFile( base_path() . '/uploads/'. $path ,'.jpg');
        $savedFile = $this->fileService->store($file,'0');
        if (is_string($savedFile)) {
            return response()->json([
                'error' => $savedFile,
            ], 409);
        }
        event(new FileWasUploaded($savedFile));
        return $savedFile->id;
    }
}
