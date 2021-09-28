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


declare(strict_types=1);

namespace Catcher\Support\Generate\Create;

use Catcher\CatchAdmin;
use Catcher\Exceptions\FailedException;
use Illuminate\Support\Facades\File;
use JaguarJack\Generate\Build\ClassConstFetch;
use JaguarJack\Generate\Build\MethodCall;
use JaguarJack\Generate\Build\Value;
use JaguarJack\Generate\Generator;
use JaguarJack\Generate\Types\Array_;
use JaguarJack\Generate\Types\String_;
use PhpParser\Comment\Doc;
use PhpParser\Node\Arg;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\ParserFactory;
use Illuminate\Support\Str;

class CreateRoute extends Creator
{
    protected $module;

    /**
     * generate
     *
     * @time 2021年07月26日
     * @param string $name controller name
     * @return bool
     * @throws \Exception
     */
    public function generate(string $name = ''):bool
    {
        if (CatchAdmin::isModuleRouteExists($this->module)) {
            $this->addRoute($name);
        } else {
            $this->generateRoute();
        }

        return true;
    }

    /**
     * generate route
     *
     * @time 2021年07月30日
     * @throws \Exception
     * @return void
     */
    protected function generateRoute()
    {
        $generator = new Generator();

        $useStmt = (new Use_([
            new UseUse(new Name('Illuminate\Support\Facades\Route'))
        ]));

        $expr = $generator->call('prefix', [Value::fetch($this->module)],'Route')
            ->call('middleware', [new Array_(config('catch.middlewares'))])
            ->call('group', [$generator->closure()])
            ->call();

        File::put(CatchAdmin::getModuleRoutePath($this->module),
            '<?php' . PHP_EOL . PHP_EOL .
            $generator->getContent([$useStmt, $expr]) . ';');

        if (! File::exists(CatchAdmin::getModuleRoutePath($this->module))) {
            throw new FailedException('generate failed');
        }
    }

    /**
     * add route
     *
     * @time 2021年07月30日
     * @param $controller
     * @return bool
     * @throws \Exception
     */
    protected function addRoute($controller): bool
    {
        $generator = new Generator();

        $controllerNamespace = CatchAdmin::getModuleControllerNamespace($this->module) . ucfirst($controller);

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        $ast = $parser->parse(File::get(CatchAdmin::getModuleRoutePath($this->module)));

        /* @var $routeExpress Expression */
        $routeExpress = array_pop($ast);
        $routeExpress->setDocComment(new Doc(PHP_EOL));

        array_push($ast, (new Use_([
            new UseUse(new Name($controllerNamespace))
        ])));

        $stmt = $generator->staticMethodCall(['Route', 'apiResource'], [
            new Arg(new String_(Str::replace('Controller', '', lcfirst($controller)))),
            new Arg(new ClassConstFetch(ucfirst($controller), 'class'))
        ]);

        $routeExpress->expr->args[0]->value->stmts[] = new Expression($stmt);

        array_push($ast, $routeExpress);

        File::put(CatchAdmin::getModuleRoutePath($this->module),
            '<?php' . PHP_EOL . PHP_EOL .
            $generator->getContent($ast));

        return true;
    }

    /**
     * remove route
     *
     * @time 2021年07月30日
     * @throws \Exception
     * @return bool
     */
    public function removeRoute(): bool
    {
        $generator = new Generator();

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        $ast = $parser->parse(File::get(CatchAdmin::getModuleRoutePath($this->module)));

        /* @var $routeExpress Expression */
        $routeExpress = array_pop($ast);

        // 丢弃最后加入的 controller namespace
        array_pop($ast);
        // 丢弃最后一个路由
        $stmts = $routeExpress->expr->args[0]->value->stmts;
        array_pop($stmts);
        $routeExpress->expr->args[0]->value->stmts = $stmts;
        // 重新设置
        $routeExpress->setDocComment(new Doc(PHP_EOL));
        array_push($ast, $routeExpress);

        File::put(CatchAdmin::getModuleRoutePath($this->module),
            '<?php' . PHP_EOL . PHP_EOL .
            $generator->getContent($ast));

        return true;
    }
}
