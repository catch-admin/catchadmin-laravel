<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace Catcher\Support\Form;

use Catcher\Exceptions\FailedException;
use Catcher\Support\Form\Actions\Destroy;
use Catcher\Support\Form\Actions\Store;
use Catcher\Support\Form\Actions\Update;
use Catcher\Support\Form\Fields\Area;
use Catcher\Support\Form\Fields\Avatar;
use Catcher\Support\Form\Fields\Boolean;
use Catcher\Support\Form\Fields\Cascader;
use Catcher\Support\Form\Fields\Date;
use Catcher\Support\Form\Fields\DateTime;
use Catcher\Support\Form\Fields\Editor;
use Catcher\Support\Form\Fields\Email;
use Catcher\Support\Form\Fields\FileUpload;
use Catcher\Support\Form\Fields\Hidden;
use Catcher\Support\Form\Fields\ImageUpload;
use Catcher\Support\Form\Fields\Number;
use Catcher\Support\Form\Fields\Password;
use Catcher\Support\Form\Fields\Radio;
use Catcher\Support\Form\Fields\Select;
use Catcher\Support\Form\Fields\SelectMultiple;
use Catcher\Support\Form\Fields\Text;
use Catcher\Support\Form\Fields\Textarea;
use Catcher\Support\Form\Fields\Tree;
use Catcher\Support\Form\Fields\Url;
use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Catcher\Support\Form\Fields\Traits\RelationsTrait;

/**
 * @method static Area area(string $name, string $title, array $props = []);
 * @method static Avatar avatar(string $name, string $title, string $action, bool $auth = true);
 * @method static Boolean boolean(string $name, string $title);
 * @method static Date date(string $name, string $title);
 * @method static DateTime datetime(string $name, string $title);
 * @method static Editor editor(string $name, string $title);
 * @method static Email email(string $name, string $title);
 * @method static FileUpload fileUpload(string $name, string $title, string $action, bool $auth = true);
 * @method static ImageUpload imageUpload(string $name, string $title, string $action, bool $auth = true);
 * @method static Number number(string $name, string $title, $value = '');
 * @method static Password password(string $name, string $title);
 * @method static Select select(string $name, string $title);
 * @method static SelectMultiple selectMultiple(string $name, string $title);
 * @method static Text text(string $name, string $title);
 * @method static Textarea textarea(string $name, string $title);
 * @method static Tree tree(string $name, string $title);
 * @method static Url url(string $name, string $title);
 * @method static Cascader cascader(string $name, string $title, $options = null);
 * @method static Hidden hidden(string $name, $value = '');
 * @method static Radio radio(string $name, $value = '');
 * @time 2021年08月11日
 */
class CatchForm implements \ArrayAccess, \Iterator
{
    use RelationsTrait;

    /**
     * @var Closure
     */
    protected $save;

    /**
     * @var Closure
     */
    protected $create;

    /**
     * @var Closure
     */
    protected $update;

    /**
     * @var Closure
     */
    protected $destroy;

    /**
     * query condition
     *
     * @var array
     */
    protected $condition = [];

    /**
     * will store in database
     *
     * @var array
     */
    protected $data = [];

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var null|Closure
     */
    protected $beforeSave;

    /**
     * @var null|Closure
     */
    protected $beforeUpdate;

    /**
     * @var null|Closure
     */
    protected $beforeDestroy;

    /**
     * @var Closure
     */
    protected $afterSave;

    /**
     * @var Closure
     */
    protected $afterUpdate;

    /**
     * @var Closure
     */
    protected $afterDestroy;


    /**
     * @var boolean
     */
    protected $autoWriteCreatorId = true;

    /**
     * create form
     *
     * @time 2021年08月11日
     * @param Closure $create
     * @param string $model
     * @return CatchForm
     */
    public function creating(Closure $create, string $model): CatchForm
    {
        $this->create = $create;

        $this->model = $model;

        return $this;
    }

    /**
     * when
     *
     * @time 2021年09月13日
     * @param $condition
     * @param Closure $callback
     * @return $this
     */
    public function when($condition, Closure $callback): CatchForm
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }

    /**
     *
     * @time 2021年08月11日
     * @param Closure $save
     * @return $this
     */
    public function saving(Closure $save): CatchForm
    {
        $this->save = $save;

        return $this;
    }

    /**
     * update
     *
     * @time 2021年08月11日
     * @param Closure $update
     * @return $this
     */
    public function updating(Closure $update): CatchForm
    {
        $this->update = $update;

        return $this;
    }

    /**
     * destroy it
     *
     * @time 2021年08月11日
     * @param Closure $destroy
     * @return $this
     */
    public function destroying(Closure $destroy): CatchForm
    {
        $this->destroy = $destroy;

        return $this;
    }

    /**
     * prepare
     *
     * @time 2021年08月11日
     * @param Closure $closure
     * @return $this
     */
    public function prepare(Closure $closure): self
    {
        $closure($this);

        return $this;
    }

    /**
     * before save
     *
     * @time 2021年08月11日
     * @param Closure $closure
     * @return CatchForm
     */
    public function beforeSave(Closure $closure): self
    {
        $this->beforeSave = $closure;

        return $this;
    }

    /**
     * before update
     *
     * @time 2021年08月11日
     * @param Closure $closure
     * @return $this
     */
    public function beforeUpdate(Closure $closure): self
    {
        $this->beforeUpdate = $closure;

        return $this;
    }


    /**
     * before destroy
     *
     * @time 2021年08月11日
     * @param Closure $closure
     * @return $this
     */
    public function beforeDestroy(Closure $closure): self
    {
        $this->beforeDestroy = $closure;

        return $this;
    }

    /**
     * store
     *
     * @time 2021年08月11日
     * @return false|mixed
     * @throws BindingResolutionException
     */
    public function store()
    {
        if ($this->beforeSave instanceof Closure) {
            call_user_func($this->beforeSave, $this);
        }

        $res = (new Store($this))->run();

        if ($this->afterSave instanceof Closure) {
            call_user_func($this->afterSave, $this);
        }

        return $res;
    }

    /**
     * update
     *
     * @time 2021年08月11日
     * @return false|mixed
     * @throws BindingResolutionException
     */
    public function update()
    {
        if ($this->beforeUpdate instanceof Closure) {
            call_user_func($this->beforeUpdate, $this);
        }

        $res = (new Update($this))->run();

        if ($res) {
            if ($this->afterUpdate instanceof  Closure) {
                call_user_func($this->afterUpdate, $this);
            }
        }

        return $res;
    }

    /**
     * destroy
     *
     * @time 2021年08月11日
     * @return false|mixed
     * @throws BindingResolutionException
     */
    public function destroy()
    {
        if ($this->beforeDestroy instanceof Closure) {
            call_user_func($this->beforeDestroy, $this);
        }

        if ($res = (new Destroy($this))->run()) {
            if ($this->afterDestroy instanceof  Closure) {
                call_user_func($this->afterDestroy, $this);
            }
        }

        return $res;
    }

    /**
     * after save
     *
     * @time 2021年08月26日
     * @param Closure $closure
     * @return $this
     */
    public function afterSave(Closure $closure): CatchForm
    {
        $this->afterSave = $closure;

        return $this;
    }

    /**
     * after update
     *
     * @time 2021年08月26日
     * @param Closure $closure
     * @return $this
     */
    public function afterUpdate(Closure $closure): CatchForm
    {
        $this->afterUpdate = $closure;

        return $this;
    }

    /**
     * after destroy
     *
     * @time 2021年08月26日
     * @param Closure $closure
     * @return $this
     */
    public function afterDestroy(Closure $closure): CatchForm
    {
        $this->afterDestroy = $closure;

        return $this;
    }

    /**
     * fields
     *
     * @time 2021年08月11日
     * @return array
     * @throws BindingResolutionException
     */
    public function create(): array
    {
        $fields = [];

        $created = call_user_func($this->create);

        foreach ($created as $field) {
            $field = $this->parseRelate($field);

            $fields[] = $field();
        }

        return $fields;
    }

    /**
     * set condition
     *
     * @time 2021年08月11日
     * @param array $condition
     * @return $this
     */
    public function setCondition(array $condition): CatchForm
    {
        $this->condition = $condition;

        return $this;
    }


    /**
     * set data
     *
     * @time 2021年08月11日
     * @param array $data
     * @return $this
     */
    public function setData(array $data): CatchForm
    {
        $this->data = $data;

        return $this;
    }

    /**
     * get condition
     *
     * @time 2021年08月19日
     * @return array
     */
    public function getCondition(): array
    {
        return $this->condition;
    }

    /**
     * get data
     *
     * @time 2021年08月19日
     * @return array
     * @throws BindingResolutionException
     */
    public function getData(): array
    {
        // auto write creator_id
        if ($this->autoWriteCreatorId && in_array('creator_id', $this->getModel()->getFillable())) {
            $this->data['creator_id'] = Auth::id();
        }

        return $this->data;
    }

    /**
     * close write creator id
     *
     * @time 2021年09月22日
     * @return $this
     */
    public function dontWriteCreatorId(): CatchForm
    {
        $this->autoWriteCreatorId = false;

        return $this;
    }

    /**
     * get model of form
     *
     * @time 2021年08月11日
     * @return Model
     * @throws BindingResolutionException
     */
    public function getModel(): Model
    {
        if (is_string($this->model)) {
            $this->model = app()->make($this->model);
        }

        return $this->model;
    }

    /**
     * col
     *
     * @time 2021年08月17日
     * @param int $col
     * @param mixed $fields
     * @return array
     */
    public static function col(int $col, $fields): array
    {
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $field->col($col);
            }
        } else {
            $fields->col($col);
        }

        return $fields;
    }

    /*
     *
     * @time 2021年08月19日
     * @return Closure
     */
    public function getSaving(): Closure
    {
        return $this->save;
    }

    /**
     *
     * @time 2021年08月19日
     * @return Closure
     */
    public function getUpdating(): Closure
    {
        return $this->update;
    }

    /**
     *
     * @time 2021年08月19日
     * @return Closure
     */
    public function getDestroying(): Closure
    {
        return $this->destroy;
    }

    /**
     *
     * @time 2021年08月26日
     * @return Closure|null
     */
    public function getBeforeSave(): ?Closure
    {
        return $this->beforeSave;
    }

    /**
     *
     * @time 2021年08月26日
     * @return Closure|null
     */
    public function getBeforeUpdate(): ?Closure
    {
        return $this->beforeUpdate;
    }

    /**
     *
     * @time 2021年08月26日
     * @return Closure|null
     */
    public function getBeforeDestroy(): ?Closure
    {
        return $this->beforeDestroy;
    }

    /**
     * get primary key value
     *
     * @time 2021年09月15日
     * @return mixed
     * @throws BindingResolutionException
     */
    public function getPrimaryKeyValue()
    {
        return $this->condition[$this->getModel()->getKeyName()];
    }

    /**
     * invoke
     *
     * @time 2021年09月23日
     * @return array
     */
    public function __invoke(): array
    {
        return $this->data;
    }

    /**
     *
     * @time 2021年08月11日
     * @param $name
     * @param $arguments
     * @return false|mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([__NAMESPACE__ . '\\Fields\\' . ucfirst($name), 'make'], $arguments);
    }

    /**
     * get value
     *
     * @time 2021年09月15日
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        throw new FailedException(sprintf('{%s} Not Exist', $key));
    }

    /**
     * set value
     *
     * @time 2021年09月15日
     * @param $key
     * @param $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * isset key in data
     *
     * @time 2021年09月15日
     * @param $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * unset key from data
     *
     * @time 2021年09月15日
     * @param $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->data[$key]);
    }

    public function offsetExists($offset): bool
    {
        // TODO: Implement offsetExists() method.
        return isset($this->data[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
        $this->data[$offset] = $value;

        return $this;
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
        return $this->data[$offset];
    }

    public function key()
    {
        // TODO: Implement key() method.
        return key($this->data);
    }

    public function next()
    {
        // TODO: Implement next() method.
        return next($this->data);
    }

    public function current()
    {
        // TODO: Implement current() method.
        return current($this->data);
    }

    public function valid()
    {
        // TODO: Implement valid() method.
        return $this->current() !== false;
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
        return reset($this->data);
    }
}
