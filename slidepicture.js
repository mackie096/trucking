/* Slide script */
var modal = document.getElementById('id01');
var myIndex = 0;
markslide();

function markslide(){
    var i;
    var x = document.getElementsByClassName("slide");
    for (i = 0; i < x.length; i++) {
      x[i].style.display = "none";  
    }
    myIndex++;
    if (myIndex > x.length) {myIndex = 1}    
    x[myIndex-1].style.display = "block";  
    setTimeout(markslide, 3500);
}
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}