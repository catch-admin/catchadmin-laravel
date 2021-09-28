<?php
namespace Catcher\Macros;

use Illuminate\Database\Schema\Blueprint as LaravelBlueprint;

class Blueprint
{
    /**
     * created unix timestamp
     *
     * @time 2021年09月16日
     * @return void
     */
    public function createdAt()
    {
        LaravelBlueprint::macro(__FUNCTION__, function (){
            $this->unsignedInteger('created_at')->default(0)->comment('创建时间');
        });
    }

    /**
     * update unix timestamp
     *
     * @time 2021年09月16日
     * @return void
     */
    public function updateAt()
    {
        LaravelBlueprint::macro(__FUNCTION__, function (){
            $this->unsignedInteger('updated_at')->default(0)->comment('更新时间');
        });
    }

    /**
     * soft delete
     *
     * @time 2021年09月16日
     * @return void
     */
    public function deletedAt()
    {
        LaravelBlueprint::macro(__FUNCTION__, function (){
            $this->unsignedInteger('deleted_at')->default(0)->comment('删除时间');
        });
    }


    /**
     * unix timestamp
     *
     * @time 2021年09月16日
     * @return void
     */
    public function unixTimestamp()
    {
        LaravelBlueprint::macro(__FUNCTION__, function ($softDeleted = true){
            $this->createdAt();
            $this->updateAt();

            if ($softDeleted) {
                $this->deletedAt();
            }
        });
    }

    /**
     * creator id
     *
     * @time 2021年09月16日
     * @return void
     */
    public function creatorId()
    {
        LaravelBlueprint::macro(__FUNCTION__, function (){
            $this->unsignedInteger('creator_id')->default(0)->comment('创建人ID');
        });
    }


    /**
     * parent ID
     *
     * @time 2021年09月16日
     * @return void
     */
    public function parentId()
    {
        LaravelBlueprint::macro(__FUNCTION__, function (){
            $this->unsignedInteger('parent_id')->default(0)->comment('父级ID');
        });
    }


    /**
     * status
     *
     * @time 2021年09月16日
     * @return void
     */
    public function status()
    {
        LaravelBlueprint::macro(__FUNCTION__, function ($default = 1){
            $this->tinyInteger('status')->default($default)->comment('1 正常 2 禁用');
        });
    }

    /**
     * sort
     *
     * @time 2021年09月16日
     * @return void
     */
    public function sort()
    {
        LaravelBlueprint::macro(__FUNCTION__, function ($default = 1){
            $this->integer('sort')->comment('排序')->default($default);
        });
    }
}
