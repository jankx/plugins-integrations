<script>
    // When DOM document is ready
    window.addEventListener('DOMContentLoaded', function () {
        var toogleMenuItem = document.querySelector('.menu-item-type-vertical_ubermenu');
        var ubermenuItem = document.querySelector('.jankx-integrate-ubermenu--vertical');
        if (toogleMenuItem && ubermenuItem) {
            toogleMenuItem.addEventListener('mouseover', function() {
                if (ubermenuItem.classList) {
                    if(!ubermenuItem.classList.contains('show')) {
                        ubermenuItem.classList.add('show');
                    }
                } else {
                    // This is hack for IE9
                    var classes = ubermenuItem.className.split(" ");
                    var i = classes.indexOf("show");

                    if (i < 0) {
                        classes.push("show");
                        ubermenuItem.className = classes.join(" ");
                    }
                }
            });
            toogleMenuItem.addEventListener('mouseout', function() {
                if (ubermenuItem.classList) {
                    if(ubermenuItem.classList.contains('show')) {
                        ubermenuItem.classList.remove('show');
                    }
                } else {
                    // This is hack for IE9
                    var classes = ubermenuItem.className.split(" ");
                    var i = classes.indexOf("show");

                    if (i >= 0) {
                        classes.splice(i, 1);
                        ubermenuItem.className = classes.join(" ");
                    }
                }
            });
        }
    });
</script>
