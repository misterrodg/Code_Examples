<?php
  namespace App\Http\Controllers\admin;
  use DB;
  use Carbon;
  use Cookie;
  use File;
  use Hash;
  use Session;
  use App\models\admins;
  use App\models\auth;
  use App\models\content;
  use App\Http\Requests;
  use App\Http\Controllers\Controller;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Redirect;
  use Illuminate\Support\Facades\Validator;
  use App\customclasses\Corefunctions;

  class ContentController extends Controller {
    public function __construct(Request $request){
  		$this->Corefunctions = new \App\customclasses\Corefunctions;
  		$this->Corefunctions->adminSessionCheck();
  	}

   	public function listing(Request $request){

  		$result = content::getAllContent();

  		$data['keywords']     = '';
  		$data['description']  = '';
  		$data['titlecls']     = '';
  		$data['title']        = 'Content';
  		$data['titlecls']     = 'Content';
      $data['leftcls']      = 'content';
  	  $data['result']       = $result;
      $data['pagetitle']    = 'Content';

  		return view('admin.content.listing', $data);
    }

    public function create(Request $request ){
      if ($request->Input('mode') and $request->Input('mode') == '1') {

        // Content Validation Check
        $validation = Validator::make($request->all(), array(
          'contentname'  => 'required',
          'description'  => 'required',
          'contentimage' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ));

        if ($validation->fails()) {
          return Redirect::to(ADMIN .'/content/create')->with('message', 'Please enter required details')->withInput();
        } else {

          $Corefunctions = new \App\customclasses\Corefunctions;

          if ($request->hasFile('thumbnailimage')) {
            // Upload image to server
            // Removed to avoid showing paths in example

            // Insert into Content
            $contentid = content::insertContent($request->Input(),$contentfilext);
            return Redirect::to(ADMIN . '/content')->withInput();
          } else {
            return Redirect::to(ADMIN . '/content/create')->withInput();
          }
        }
      }

      $data['leftcls']     = 'content';
      $data['title']       = 'Create Content';

      return view('admin.content.create', $data);
    }

  public function edit(Request $request, $contentkey){
    if (!$contentkey) {
      return Redirect::to(ADMIN. '/content');
    }

    // Fetch partner details by id
    $contentDetails = lounge::contentByKey($contentkey);
    $contentid = $contentDetails['contentid'];

    if (empty($contentDetails)) {
      return Redirect::to(ADMIN. '/content');
    }

    if ($request->Input('mode') and $request->Input('mode') == '1') {

        // Partner Validation Check
        $validation = Validator::make($request->all(), array(
            'contentname'    => 'required',
            'description'    => 'required',
            'thumbnailimage' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ));

        if ($validation->fails()) {
          return Redirect::to(ADMIN .'/content/edit/'. $contentkey)->withInput();
        } else {

          $Corefunctions = new \App\customclasses\Corefunctions;

          if ($request->hasFile('cocktailimage')) {
            // Upload image to server
            // Removed to avoid showing paths in example

            // Update Partners
            $cocktailid = lounge::updateCocktail($request->Input(),$cocktailid,$cocktailfilext);
            return Redirect::to(ADMIN . '/lounge')->with('succmessage',"Partner updated successfully.")->withInput();
          } else {
            lounge::updateCocktail($request->Input(),$cocktailid);
            return Redirect::to(ADMIN . '/lounge')->with('succmessage',"Partner updated successfully.")->withInput();
          }
        }
    }

    $data['cocktailDetails']   = $cocktailDetails;
    $data['leftcls']          = 'lounge';
    $data['title']            = 'Edit Cocktail';
    $data['titlecls']         = 'edit cocktail';

    return view('admin.lounge.edit', $data);
  }

  protected function changeStatus(){
    if (request()->ajax()) {
      $data = request()->all();
      if (!$data['contentid'] or !$data['status']) {
        $arr['error']    = 1;
        $arr['errormsg'] = 'Fields missing';
        return response()->json($arr);
        exit();
      }
      /* Check whether respective key is available or not */
      $row = content::contentByID($data['contentid']);
      /* If specific row not present return error */
      if (!$row) {
        $arr['error']    = 1;
        $arr['errormsg'] = 'Invalid ID';
        return response()->json($arr);
        exit();
      }
      /* Status Change */
      if ($data['act'] == 'statchange') {
        $stat = ( $data['status'] == 'public' ) ? 0 : 1;
        content::updateContentStatus($data['contentid'],$stat);
      }
      $arr['success'] = 1;
      return response()->json($arr);
      exit();
    }
  }

  protected function delete(){
    if (request()->ajax()) {
      $data = request()->all();
      if (!$data['contentid']) {
        $arr['error']    = 1;
        $arr['errormsg'] = 'Fields missing';
        return response()->json($arr);
        exit();
      }
      /* Check whether respective key is available or not */
      $row = content::contentByID($data['contentid']);
      /* If specific row not present return error */
      if (!$row) {
        $arr['error']    = 1;
        $arr['errormsg'] = 'Invalid ID';
        return response()->json($arr);
        exit();
      }
      /* Admin Status Change */
      if ($data['act'] == 'delete') {
        content::softDeleteContent($data['contentid']);
      }
      $arr['success'] = 1;
      return response()->json($arr);
      exit();
    }
  }
}
