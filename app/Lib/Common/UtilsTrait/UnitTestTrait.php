<?php
namespace App\Lib\Common\UtilsTrait;



trait UnitTestTrait {


    /**
     * @return bool
     * @throws \Exception
     */
    protected function canTest()
    {
        //为调试模式 才可以

        if(config('app.debug') && config('app.env') =="testing"){
            return true;
        }else{
            throw new \Exception("调试模式且phpunit执行才能测试");
        }

    }


    /**
     * @param $fun_name
     * @param mixed ...$string
     * @return mixed
     * @throws \Exception
     */
    public function test($fun_name, ...$string)
    {
        if ($this->canTest()) {
            return $this->$fun_name(...$string);

        }

    }


    /**
     * @param $name
     * @param $value
     * @throws \Exception
     */
    public function __set($name, $value){

        if ($this->canTest()) {
            $this->$name = $value;

        }

    }


    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if ($this->canTest()) {
            return $this->$name;
        }
    }
}
