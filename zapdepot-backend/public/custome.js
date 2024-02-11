let element = document.getElementsByClassName("hl_without-topbar");
let elementQuery = document.querySelector(".hl_without-topbar");
let checkVal = false;
let timer;

var htmlSting = `<div class="modal-from-custome">
<div class="modal-from-custome-content">
  <div class="wistia_responsive_padding" style="padding:58.25% 0 0 0;position:relative;">
    <div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;">
      <div class="wistia_embed wistia_async_fq1q8gqpj4 videoFoam=true" style="height:100%;position:relative;width:100%">
        <div class="wistia_swatch" style="height:100%;left:0;opacity:0;overflow:hidden;position:absolute;top:0;transition:opacity 200ms;width:100%;">
          <img src="https://fast.wistia.com/embed/medias/fq1q8gqpj4/swatch" style="filter:blur(5px);height:100%;object-fit:contain;width:100%;" alt="" aria-hidden="true" onload="this.parentNode.style.opacity=1;" />
        </div>
      </div>
    </div>
  </div>
  <div style="width:100%;text-align:center;">
    <span class="close-button-modal-from-custome" onclick="closeButton()">Close</span>
  </div>
</div>
</div>`  
 
function checkElements() {
  if (element.length) {
    checkVal = true;
    if (checkVal) {
      if(localStorage.getItem('customPopUp') == null){
      var div = document.createElement("div");
      div.className = "custome-className-from-script";
      div.id = "custome-IdName-from-script-id";
      div.innerHTML += htmlSting
      element[0].appendChild(div);
      toggleModal() 
      clearTimeout(timer); 
      localStorage.setItem('customPopUp','shown')
      }
    }
  } else { 
    console.log("Call Function Retry");
  }
}
 
if ((!checkVal) && (localStorage.getItem('customPopUp') == null)) { 
  timer = setInterval(function () {
    checkElements();
  }, 5000);
} 


function toggleModal() {
  var modal = document.querySelector(".modal-from-custome");
  console.log(modal)
  modal.classList.toggle("show-modal-modal-from-custome");
}

function closeButton() { 
  $('video').trigger('pause');
  toggleModal() 

}