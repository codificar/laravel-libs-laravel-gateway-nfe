<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class AddNfeGatewayMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $permission = \Permission::updateOrCreate(
            ['parent_id' => 2319, 'name' => 'admin_nfe_gateway', 'order' => 916],
            [
                'name' => 'admin_nfe_gateway',
                'parent_id' => 2319,
                'order' => 916,
                'is_menu' => 1,
                'url' => '/admin/libs/settings/nfe_gateway',
                'icon' => 'mdi mdi-receipt'
            ]
        );       

        \PermissionSubAction::updateOrCreate(['name' => 'admin_nfe_gateway', 'parent_id' => '2320'], ['name' => 'admin_nfe_gateway', 'parent_id' => '2320']);       

		if($permission){
            $profile1 = \Profile::find(1);
            $havePermission = \ProfilePermission::where('permission_id', $permission->id)->where('profile_id', 1)->count();
			if($profile1 && $havePermission <= 0) $profile1->permissions()->attach($permission->id);

            $profile4 = \Profile::find(4);
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
