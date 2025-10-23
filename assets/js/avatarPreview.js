document.addEventListener('DOMContentLoaded',function(){
    const input=document.getElementById('upload-input');
    const label=document.getElementById('upload-label');
    const modal=document.getElementById('avatar-modal');
    const cropArea=document.getElementById('avatar-crop');
    const img=document.getElementById('avatar-crop-image');
    const zoom=document.getElementById('avatar-zoom');
    const cancelBtn=document.getElementById('avatar-cancel');
    const applyBtn=document.getElementById('avatar-apply');
    const hiddenOut=document.getElementById('avatar-cropped');

    if(!input||!label||!modal||!img) return;

    let s=1, tx=0, ty=0, startX=0, startY=0, dragging=false, naturalW=0, naturalH=0;

    function openModal(url){
        img.src=url;
        s=1;tx=0;ty=0;zoom.value=1;
        modal.classList.remove('hidden');
    }
    function closeModal(){
        modal.classList.add('hidden');
    }
    function applyTransform(){
        img.style.transform=`translate(calc(-50% + ${tx}px),calc(-50% + ${ty}px)) scale(${s})`;
    }

    input.addEventListener('change',function(){
        const f=input.files&&input.files[0];
        if(!f) return;
        const url=URL.createObjectURL(f);
        img.onload=function(){naturalW=img.naturalWidth;naturalH=img.naturalHeight;applyTransform()};
        openModal(url);
    });

    cropArea.addEventListener('mousedown',function(e){
        dragging=true;cropArea.classList.add('dragging');startX=e.clientX;startY=e.clientY;
    });
    window.addEventListener('mouseup',function(){dragging=false;cropArea.classList.remove('dragging')});
    window.addEventListener('mousemove',function(e){
        if(!dragging) return;
        tx+=e.clientX-startX;ty+=e.clientY-startY;startX=e.clientX;startY=e.clientY;applyTransform();
    });
    cropArea.addEventListener('wheel',function(e){
        e.preventDefault();
        const rect=cropArea.getBoundingClientRect();
        const cx=e.clientX-rect.left-rect.width/2;
        const cy=e.clientY-rect.top-rect.height/2;
        const k=Math.exp(-e.deltaY*0.0015);
        const ns=Math.min(3,Math.max(0.5,s*k));
        const ds=ns/s;
        tx=tx*ds+cx*(1-ds);
        ty=ty*ds+cy*(1-ds);
        s=ns;zoom.value=s;applyTransform();
    },{passive:false});
    zoom.addEventListener('input',function(){
        const rect=cropArea.getBoundingClientRect();
        const cx=0, cy=0;
        const ns=parseFloat(this.value);
        const ds=ns/s;
        tx=tx*ds+cx*(1-ds);
        ty=ty*ds+cy*(1-ds);
        s=ns;applyTransform();
    });

    cancelBtn.addEventListener('click',function(){closeModal()});

    applyBtn.addEventListener('click',function(){
        const rect=cropArea.getBoundingClientRect();
        const size=512;
        const canvas=document.createElement('canvas');
        canvas.width=size;canvas.height=size;
        const ctx=canvas.getContext('2d');
        const cx=size/2, cy=size/2;
        ctx.beginPath();ctx.arc(cx,cy,size/2*0.88,0,Math.PI*2);ctx.closePath();ctx.clip();
        const scale=s*(size/rect.width);
        ctx.setTransform(scale,0,0,scale,cx+tx*(size/rect.width),cy+ty*(size/rect.height));
        ctx.drawImage(img,-naturalW/2,-naturalH/2);
        const dataUrl=canvas.toDataURL('image/png');
        hiddenOut.value=dataUrl;
        label.innerHTML='';
        const p=new Image();p.src=dataUrl;p.className='preview-image';label.appendChild(p);
        input.value='';
        closeModal();
    });
});