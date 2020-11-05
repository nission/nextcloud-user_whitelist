<div id="app-settings">
	<div id="app-settings-header">
	    <button class="settings-button" data-apps-slide-toggle="#app-settings-content"></button>
	</div>
	<div id="app-settings-content">
		<table>
		<thead>
		<tr><th>用户名</th><th>状态</th><th>添加时间</th><th>最后更新时间</th><th>备注</th></tr>
		</thead>
		<tbody>
		<?php foreach($_['users'] as $user) { ?>
		<tr>
		<td>
			<?php echo $user->getName(); ?>
		</td>
		<td>
			<?php echo $user->getStatus() == 2 ? '生效' : '失效'; ?>
		</td>
		<td>
			<?php echo $user->getCreate(); ?>
		</td>
		<td>
			<?php echo $user->getEdit(); ?>
		</td>
		<td>
			<?php echo $user->getRemark(); ?>
		</td>
		</tr>
        <?php } ?>
		</tbody>
		</table>
	</div>
</div>
