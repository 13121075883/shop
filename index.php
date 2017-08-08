<?php
namespace app\admin\controller;
use think\Controller;

class City extends Controller
{
    public function index()
    {
        $parentId =  input('get.parent_id',0,'intval');
        $citys = model("city")->getFirstCity($parentId);
        return $this->fetch('',[ 'citys'=>$citys, ]);
    }

    //显示添加 页面  的方法
    public function add()
    {
        $citys = model("city")->getNormalFirstcity();

        return $this->fetch('',[ 'citys'=>$citys, ]);
    }


    //添加方法
    public function save()
    {
        /**
         * 做下严格判定
         */
        if(!request()->isPost())
        {
            $this->error("请求失败");
        }


        $data = input('post.');                                //接收post值
        //引用验证规则
        $validate = validate('city');
        //如果验证失败  或是成功
        if(!$validate->scene('add')->check($data))
        {
            $this->error($validate->getError());

        }


        //如果这个id不为空
        if(!empty($data['id']))
        {
            return $this->update($data);
        }




        //把data提交给model  插入到数据库
        $res = model('city')->add($data);

        if($res){
            $this->success('插入分类成功');
        }else{
            $this->error("数据库插入失败");
        }
    }



    /**
     * 编辑页面
     */
    public  function edit($id = 0)
    {
        if(intval($id) < 1){
            $this->error('参数不合法');
        }
        //查找这个id的信息
        $city = model('city')->get($id);      //与find()类似 只不过get返回的是对象
        $citys = model("city")->getNormalFirstCity();     //获取等级栏目
        return $this->fetch('',[ 'city'=>$city,'citys'=>$citys, ]);
    }


    //更新方法
    public function update($data)
    {
        $res =  model('city')->save($data, ['id'=> intval($data['id'])]);
        if($res){
            $this->success("更新成功");
        }else{
            $this->error("更新失败");
        }
    }


     /**
     * 修改状态方法
     */
    public function status()
    {
        $data = input('get.');                                //接收post值
        //引用验证规则
        $validate = validate('city');
        //如果验证失败  或是成功
        if(!$validate->scene('status')->check($data))
        {
            $this->error($validate->getError());

        }


        $res = model('city')->save(['status'=>$data['status']],['id'=>$data['id']]);
        if($res){

            $this->success("状态更新成功",'index');

        }else{

            $this->error("状态更新失败","index");

        }

    }



    //ajax  排序方法
    /**
     * @param $id          要排序的id
     * @param $listorder   排序值
     */
    public function listorder($id,$listorder)
    {
        //存入值  id等于id的
        $res =  model('city')->save(['listorder'=>$listorder],['id'=>$id]);

        if($res){

            //返回tp自带的标准字符串
            $this->result($_SERVER['HTTP_REFERER'],1,'success');

        }else{

            $this->result($_SERVER['HTTP_REFERER'],0,'更新失败');

        }
    }
}