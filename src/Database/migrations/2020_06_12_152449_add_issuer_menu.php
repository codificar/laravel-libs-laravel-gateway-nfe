<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class AddIssuerMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission =\Permission::updateOrCreate(array('id' => 6103), 
        array('name' => 'admin_issuer', 'parent_id' => 2318, 'order' => 202, 'is_menu' => 1, 'url' => '/admin/issuer/company/create', 'icon' => 'mdi mdi-human-male'));

        if($permission){
            $profile1 =\Profile::find(1);
            $havePermission = \ProfilePermission::where('permission_id', $permission->id)->where('profile_id', 1)->count();
			if($profile1 && $havePermission <= 0) $profile1->permissions()->attach($permission->id);

            $profile4 =\Profile::find(4);
            $havePermission = \ProfilePermission::where('permission_id', $permission->id)->where('profile_id', 4)->count();
			if($profile1 && $havePermission <= 0) $profile4->permissions()->attach($permission->id);
			
		}

        //List NFE
        $permission =\Permission::updateOrCreate(
            ['parent_id' => 2318, 'name' => 'admin_nfe_gateway_list', 'order' => 809],
            [
                'name' => 'admin_nfe_gateway_list',
                'parent_id' => 2318,
                'order' => 809,
                'is_menu' => 1,
                'url' => '/admin/nfe',
                'icon' => 'mdi mdi-receipt'
            ]
        );

        \PermissionSubAction::updateOrCreate(['name' => 'admin_nfe_gateway_list', 'parent_id' => '2318'], ['name' => 'admin_nfe_gateway_list', 'parent_id' => '2318']);

        if($permission){
            $profile1 =\Profile::find(1);
            $havePermission = \ProfilePermission::where('permission_id', $permission->id)->where('profile_id', 1)->count();
			if($profile1 && $havePermission <= 0) $profile1->permissions()->attach($permission->id);

            $profile4 =\Profile::find(4);
            $havePermission = \ProfilePermission::where('permission_id', $permission->id)->where('profile_id', 4)->count();
			if($profile1 && $havePermission <= 0) $profile4->permissions()->attach($permission->id);
			
		}
        

		
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
