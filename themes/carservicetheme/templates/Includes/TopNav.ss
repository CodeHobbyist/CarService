<ul class="navbar-nav nav">
  <% loop $Menu(1) %>                  
	<li class="nav-item dot-fix">
			<a class="nav-link $LinkingMode" href="$Link" title="$Title">$MenuTitle</a>
	</li>
  <% end_loop %>
</ul>