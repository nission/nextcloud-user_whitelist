<?php
script('userwhitelist', 'script');
style('userwhitelist', 'admin');
?>

<div id="whitelist_user_list">
	<table>
		<thead class="table_thead">
			<tr>
				<th>用户名</th>
				<th>状态</th>
				<th>添加时间</th>
				<th>最后更新时间</th>
				<th>备注</th>
			</tr>
		</thead>
		<tbody class="table_tbody">
			<?php foreach ($_['users'] as $user) { ?>
				<tr>
					<td>
						<?php echo $user->getName(); ?>
					</td>
					<td>
						<?php if ($user->getStatus() == 2) { ?>
						 	<span class="tag tag-green">生效</span>
				    	<?php } else { ?>
						 	<span class="tag tag-red">失效</span>
				    	<?php } ?>
					</td>
					<td>
						<?php echo $user->getCreate(); ?>
					</td>
					<td>
						<?php echo $user->getEdit(); ?>
					</td>
					<td>
						<?php $remark = $user->getRemark(); 
							if (strlen($remark) >= 32) {
								echo '...' . substr($remark, -32);
							} else {
								echo $remark;
							}
				    	?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>