function menu_goto( menuform )
{
  var baseurl = '';
  selecteditem = menuform.contenttoc_menu.selectedIndex;
  newurl = menuform.contenttoc_menu.options[ selecteditem ].value;
  if (newurl.length != 0) {
    top.location.href = baseurl + newurl;
  }
}
