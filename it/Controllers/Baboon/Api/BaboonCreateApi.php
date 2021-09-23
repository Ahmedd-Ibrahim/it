<?php
namespace Phpanonymous\It\Controllers\Baboon\Api;

use App\Http\Controllers\Controller;

class BaboonCreateApi extends Controller {

	public static function indexMethod($r) {
		$index = '
            /**
             * Baboon Script By ' . it_version_message() . '
             * Display a listing of the resource. Api
             * @return \Illuminate\Http\Response
             */
            public function index()
            {
            	${ModelName} = {ModelName}::orderBy(\'id\',\'desc\')->paginate(15);
               return response([
               "status"=>true,
               "statusCode"=>200,
               "data"=>${ModelName}
               ],200);
            }' . "\n";

		return str_replace('{ModelName}', request('model_name'), $index);
	}

	public static function storeMethod($r) {
		$objectlist = [];
		$store = '
            /**
             * Baboon Script By ' . it_version_message() . '
             * Store a newly created resource in storage. Api
             * @param  \Illuminate\Http\Request  $r
             * @return \Illuminate\Http\Response
             */
    public function store(' . request('controller_name') . 'Request $request)
    {
    	$data = $request->except("_token");
    	' . "\n";

		if ($r->has('has_user_id')) {
			$store .= '              $data[\'user_id\'] = auth()->id(); ' . "\n";
		}

		$i = 0;
		foreach (request('col_name_convention') as $conv) {
			$objectlist = [];
			if (request('col_type')[$i] == 'file') {
				$store .= '               if(request()->hasFile(\'' . $conv . '\')){' . "\n";
				$folder = str_replace('controller', '', strtolower(request('controller_name')));

				$store .= '              $data[\'' . $conv . '\'] = it()->upload(\'' . $conv . '\',\'' . $folder . '\');' . "\n";
				$store .= '              }else{' . "\n";
				$store .= '                $data[\'' . $conv . '\'] = "";' . "\n";
				$store .= '              }' . "\n";
			}
			$i++;
		}

		$store .= '        ${ModelName} = {ModelName}::create($data); ' . "\n";
		$store .= '
        return response([
            "status"=>true,
            "statusCode"=>200,
            "message"=>trans(\'{lang}.added\'),
            "data"=>${ModelName}
        ],200);
    }';
		$store = str_replace('{ModelName}', request('model_name'), $store);
		$store = str_replace('{lang}', request('lang_file'), $store);

		return $store;
	}

}
