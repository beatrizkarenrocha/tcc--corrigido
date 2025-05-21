let count = 1;
document.getElementById("radio1").checked = true;

setInterval( function(){
  nextImage();
}, 5000)

function nextImage(){
  count++;
  if(count>4){
    count=1;
  }
  
  document.getElementById("radio"+count). checked = true;
  
}
// script.js
document.addEventListener('DOMContentLoaded', function() {
  const currentPage = window.location.href.split('page=')[1] || 'dashboard';
  const menuItems = document.querySelectorAll('.sidebar-menu a');
  
  menuItems.forEach(item => {
    const href = item.getAttribute('href');
    if (href.includes(currentPage)) {
      item.classList.add('active');
    }
  });
});