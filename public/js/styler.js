var isMacLike = navigator.platform.match(/(Mac|iPhone|iPod|iPad)/i)?true:false;
if(false === isMacLike) {
    var head = document.head;
    var style = document.createElement('link');
    style.type = 'text/css';
    style.href = '/css/windows.css';
    style.rel = 'stylesheet';
    head.appendChild(style);    
}