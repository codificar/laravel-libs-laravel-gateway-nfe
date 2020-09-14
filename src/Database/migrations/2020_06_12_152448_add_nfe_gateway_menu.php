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
        $permission = Permission::updateOrCreate(
            ['parent_id' => 2319, 'name' => 'admin_nf_gateway', 'order' => 915],
            [
                'name' => 'admin_nf_gateway',
                'parent_id' => 2319,
                'order' => 915,
                'is_menu' => 1,
                'url' => '/admin/libs/settings/nfe_gateway',
                'icon' => 'mdi mdi-receipt'
            ]
        );

		if($permission){
			$profile1 = Profile::find(1);
			if($profile1)
				$profile1->permissions()->attach($permission->id);

			$profile4 = Profile::find(4);
			if($profile4)
				$profile4->permissions()->attach($permission->id);
			
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
