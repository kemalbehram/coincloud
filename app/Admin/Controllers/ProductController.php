<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\WalletType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Storage;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '产品';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->column('id', __('Id'));
        $grid->column('thumb', __('Thumb'))->display(function ($value) {
            $icon = "";
            if ($value) {
                $src = Storage::disk('oss')->url($value);
                $icon = "<img src='$src' style='max-width:30px;max-height:30px;text-align: left' class='img'/>";
            }
            return $icon; // 标题添加strong标签
        });
        $grid->column('name', __('Name'));
        $grid->column('tag', __('Tag'));
        $grid->column('price', __('Price'));
        $grid->column('price_usdt', __('Price usdt'));
        $grid->column('price_coin', __('Price coin'));
//        $grid->column('coin_wallet_address', __('Coin wallet address'));
//        $grid->column('coin_wallet_qrcode', __('Coin wallet qrcode'));
        $grid->column('wallet_type_id', __('Wallet type id'));
        $grid->column('wait_days', __('Wait days'));
        $grid->column('valid_days', __('Valid days'));
//        $grid->column('valid_days_text', __('Valid days text'));
//        $grid->column('choose_reason', __('Choose reason'));
//        $grid->column('choose_reason_text', __('Choose reason text'));
        $grid->column('service_rate', __('Service rate'));
        $grid->column('day_customer_rate', __('Day customer rate'));
        $grid->column('day_rate', __('Day rate'));
        $grid->column('freed_rate', __('Freed rate'));
//        $grid->column('parent1', __('Parent1'));
//        $grid->column('parent2', __('Parent2'));
//        $grid->column('invite_rate', __('Invite rate'));
//        $grid->column('bonus_team_a', __('Bonus team a'));
//        $grid->column('bonus_team_b', __('Bonus team b'));
//        $grid->column('bonus_team_c', __('Bonus team c'));
//        $grid->column('upgrade_team_a', __('Upgrade team a'));
//        $grid->column('upgrade_team_b', __('Upgrade team b'));
//        $grid->column('upgrade_team_c', __('Upgrade team c'));
//        $grid->column('gas_fee', __('Gas fee'));
//        $grid->column('pledge_fee', __('Pledge fee'));
//        $grid->column('pledge_days', __('Pledge days'));
//        $grid->column('valid_rate', __('Valid rate'));
//        $grid->column('package_rate', __('Package rate'));
//        $grid->column('desc', __('Desc'));
//        $grid->column('content', __('Content'));
        $grid->column('status', __('Status'))->using([
            0 => '显示',
            1 => '隐藏',
        ], '未知')->label([
            0 => 'success',
            1 => 'danger',
        ], 'warning');
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
//        $grid->column('deleted_at', __('Deleted at'));

//        $grid->disableFilter(); // 禁用查询过滤器
        $grid->disableRowSelector(); // 禁用行选择checkbox
//        $grid->disableCreateButton(); // 禁用创建按钮
//        $grid->disableActions(); // 禁用行操作列
        $grid->disableExport(); // 禁用导出数据
        $grid->disableColumnSelector();// 禁用行选择器
        $grid->actions(function ($actions) {
            $actions->disableDelete();// 去掉删除
            $actions->disableView();// 去掉查看
//            $actions->disableEdit();// 去掉编辑
        });
        $grid->model()->orderBy('id', 'desc');// 按照 ID 倒序

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('tag', __('Tag'));
        $show->field('price', __('Price'));
        $show->field('price_usdt', __('Price usdt'));
        $show->field('price_coin', __('Price coin'));
        $show->field('coin_wallet_address', __('Coin wallet address'));
        $show->field('coin_wallet_qrcode', __('Coin wallet qrcode'));
        $show->field('wallet_type_id', __('Wallet type id'));
        $show->field('wait_days', __('Wait days'));
        $show->field('valid_days', __('Valid days'));
        $show->field('valid_days_text', __('Valid days text'));
        $show->field('choose_reason', __('Choose reason'));
        $show->field('choose_reason_text', __('Choose reason text'));
        $show->field('service_rate', __('Service rate'));
        $show->field('day_customer_rate', __('Day customer rate'));
        $show->field('day_rate', __('Day rate'));
        $show->field('freed_rate', __('Freed rate'));
        $show->field('parent1', __('Parent1'));
        $show->field('parent2', __('Parent2'));
        $show->field('invite_rate', __('Invite rate'));
        $show->field('bonus_team_a', __('Bonus team a'));
        $show->field('bonus_team_b', __('Bonus team b'));
        $show->field('bonus_team_c', __('Bonus team c'));
        $show->field('upgrade_team_a', __('Upgrade team a'));
        $show->field('upgrade_team_b', __('Upgrade team b'));
        $show->field('upgrade_team_c', __('Upgrade team c'));
        $show->field('gas_fee', __('Gas fee'));
        $show->field('pledge_fee', __('Pledge fee'));
        $show->field('pledge_days', __('Pledge days'));
        $show->field('valid_rate', __('Valid rate'));
        $show->field('package_rate', __('Package rate'));
        $show->field('thumb', __('Thumb'));
        $show->field('desc', __('Desc'));
        $show->field('content', __('Content'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product());

        $form->text('name', __('Name'))->required();
        $form->text('tag', __('Tag'));
        $form->decimal('price', __('Price'))->default(0)->required()->help('人民币价格，可填写为0');
        $form->decimal('price_usdt', __('Price usdt'))->default(0)->required()->help('USDT 价格，可填写为0');
        $form->decimal('price_coin', __('Price coin'))->default(0)->required()->help('虚拟币价格，可填写为0');
        $form->text('coin_wallet_address', __('Coin wallet address'));
        $form->image('coin_wallet_qrcode', __('Coin wallet qrcode'));
        $form->select('wallet_type_id', __('Wallet type id'))->options(WalletType::where('is_enblened',1)->get()->pluck('slug', 'id'))->required();
        $form->number('wait_days', __('Wait days'))->default(0)->required();
        $form->number('valid_days', __('Valid days'))->default(0)->required();
        $form->text('valid_days_text', __('Valid days text'))->default('有效天数');
        $form->textarea('choose_reason', __('Choose reason'))->required()->help('购买产品必选理由');
        $form->text('choose_reason_text', __('Choose reason text'))->default('必选理由');
        $form->decimal('service_rate', __('Service rate'))->default(0.00)->required();
        $form->decimal('day_customer_rate', __('Day customer rate'))->default(0.00)->required();
        $form->decimal('day_rate', __('Day rate'))->default(0.00)->required();
        $form->decimal('freed_rate', __('Freed rate'))->default(0.00)->required();
        $form->decimal('parent1', __('Parent1'))->default(0.00)->required();
        $form->decimal('parent2', __('Parent2'))->default(0.00)->required();
        $form->decimal('invite_rate', __('Invite rate'))->default(0.00)->required();
        $form->decimal('bonus_team_a', __('Bonus team a'))->default(0.00)->required();
        $form->decimal('bonus_team_b', __('Bonus team b'))->default(0.00)->required();
        $form->decimal('bonus_team_c', __('Bonus team c'))->default(0.00)->required();
        $form->number('upgrade_team_a', __('Upgrade team a'))->default(0)->required();
        $form->number('upgrade_team_b', __('Upgrade team b'))->default(0)->required();
        $form->number('upgrade_team_c', __('Upgrade team c'))->default(0)->required();
        $form->decimal('gas_fee', __('Gas fee'))->default(0.00000)->required();
        $form->decimal('pledge_fee', __('Pledge fee'))->default(0.00000)->required();
        $form->number('pledge_days', __('Pledge days'))->default(1)->required();
        $form->decimal('valid_rate', __('Valid rate'))->default(0.00)->required();
        $form->decimal('package_rate', __('Package rate'))->default(0.00)->required();
        $form->image('thumb', __('Thumb'))->required();
//        $form->textarea('desc', __('Desc'));
        $form->editor('content', __('Content'))->required();
        $states = [
            'on' => ['value' => 0, 'text' => '显示', 'color' => 'primary'],
            'off' => ['value' => 1, 'text' => '隐藏', 'color' => 'danger'],
        ];

        $form->switch('status', __('Status'))->states($states);

        $form->tools(function (Form\Tools $tools) {
//            $tools->disableList(); // 去掉`列表`按钮
            $tools->disableDelete(); // 去掉`删除`按钮
            $tools->disableView(); // 去掉`查看`按钮
        });
        $form->footer(function ($footer) {
//            $footer->disableReset();  // 去掉`重置`按钮
//            $footer->disableSubmit();   // 去掉`提交`按钮
            $footer->disableViewCheck(); // 去掉`查看`checkbox
            $footer->disableEditingCheck();  // 去掉`继续编辑`checkbox
            $footer->disableCreatingCheck();// 去掉`继续创建`checkbox
        });

        return $form;
    }
}