<?php defined('ByFeiWa') or exit('Access Invalid!');?>
<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="ncm-default-form">
    <dl>
      <dt><?php echo $lang['predeposit_rechargesn'].$lang['feiwa_colon'];?></dt>
      <dd><?php echo $output['info']['pdr_sn']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['predeposit_payment'].$lang['feiwa_colon'];?></dt>
      <dd><?php echo $output['info']['pdr_payment_name']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['predeposit_recharge_price'].$lang['feiwa_colon'];?></dt>
      <dd><?php echo $output['info']['pdr_amount']; ?> <?php echo $lang['currency_zh']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['predeposit_addtime'].$lang['feiwa_colon'];?></dt>
      <dd><?php echo @date('Y-m-d H:i:s',$output['info']['pdr_add_time']); ?></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['predeposit_paytime'].$lang['feiwa_colon'];?></dt>
      <dd><?php echo intval(date('His',$output['info']['pdr_payment_time'])) ? date('Y-m-d H:i:s',$output['info']['pdr_payment_time']) : date('Y-m-d',$output['info']['pdr_payment_time']); ?></dd>
    </dl>
    <dl>
      <dt><?php echo $output['info']['pdr_payment_name'].$lang['predeposit_trade_no'].$lang['feiwa_colon'];?></dt>
      <dd><?php echo $output['info']['pdr_trade_sn']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['predeposit_paystate'].$lang['feiwa_colon'];?></dt>
      <dd><?php echo !intval($output['info']['pdr_payment_state']) ? L('predeposit_rechargewaitpaying'): L('predeposit_rechargepaysuccess'); ?></dd>
    </dl>
    <dl class="sumbit">
      <dt>&nbsp;</dt>
      <dd>
        <input type="submit"  class="submit" value="<?php echo $lang['predeposit_backlist'];?>" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER'];?>'"/>
      </dd>
    </dl>
  </div>
</div>
