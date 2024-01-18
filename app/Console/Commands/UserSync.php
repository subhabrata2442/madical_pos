<?php

namespace App\Console\Commands;

use App\Models\RoleWisePermission;
use App\Models\StoreDetails;
use App\Models\User;
use App\Models\UserRolePermission;
use Illuminate\Console\Command;

class UserSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize live server database to  local db.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Fetch data Live user table
        $liveusers = User::on('live_server_connection')->get();
        //Update or create User in Local server
        if ($liveusers) {
            foreach ($liveusers as $liveuser) {
                $localuser = User::updateOrCreate([
                    'id' => $liveuser->id,
                ], [
                    // Update the column values accordingly
                    'name'          => $liveuser->name,
                    'email'          => $liveuser->email,
                    'phone'            => $liveuser->phone,
                    'password'        => $liveuser->password,
                    'parent_id'     => $liveuser->parent_id,
                    'role'           => $liveuser->role,
                    'status'        => $liveuser->status,
                ]);

                //Start store details section
                //fetch data from live store details
                $liveStoreDetails = StoreDetails::on('live_server_connection')->where('store_id', $liveuser->id)->first();
                if ($liveStoreDetails) {
                    //update and create local store details
                    $local_store_details = StoreDetails::updateOrCreate([
                        'store_id' => $liveStoreDetails->store_id,
                    ], [
                        // Update the column values accordingly
                        'contact_email'      => $liveStoreDetails->contact_email,
                        'address'            => $liveStoreDetails->address,
                        'contact_mobile'    => $liveStoreDetails->contact_mobile,
                        'whatsapp_no'       => $liveStoreDetails->whatsapp_no,
                    ]);
                }
                //End store details
                //Start user role permission
                //fetch data from live user role permission
                $live_user_role_permissions = UserRolePermission::on('live_server_connection')->where('user_id', $liveuser->id)->get();
                if ($live_user_role_permissions) {
                    UserRolePermission::where('user_id', $liveuser->id)->delete();
                    foreach ($live_user_role_permissions as $live_user_role_permission) {
                        $userRolePermission = array(
                            'user_id'          => $live_user_role_permission->user_id,
                            'role_id'          => $live_user_role_permission->role_id,
                        );
                        UserRolePermission::create($userRolePermission);
                    }
                }
                //End user role permission
                //Start role wise permission
                $live_role_wise_permissions = RoleWisePermission::on('live_server_connection')->where('branch_id', $liveuser->id)->get();
                RoleWisePermission::where('branch_id', $liveuser->id)->delete(); //delete local role wise permission if find by user
                if ($live_role_wise_permissions) {
                    foreach ($live_role_wise_permissions as $live_role_wise_permission) {
                        $store_roll_data = array(
                            'branch_id'          => $live_role_wise_permission->branch_id,
                            'role_id'              => $live_role_wise_permission->role_id,
                            'permission_id'      => $live_role_wise_permission->permission_id,
                            'sub_permission_id' => $live_role_wise_permission->sub_permission_id,
                            'type_id'              => $live_role_wise_permission->type_id,
                        );
                        //echo '<pre>';print_r($store_roll_data);exit;
                        RoleWisePermission::create($store_roll_data);
                    }
                }
                //end role wise permission
                //Update live server user table sync status
                // User::on('live_server_connection')->where('id', $liveuser->id)->update(['db_sync' => 1]);
            }
        }

        \Log::info("Live user synchronized with the local server.");
    }
}
