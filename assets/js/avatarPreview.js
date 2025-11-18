const input=document.getElementById("upload-input")
const label=document.getElementById("upload-label")
const icon=document.getElementById("icon-container")
const wrapper=document.getElementById("preview-wrapper")
const preview=document.getElementById("preview-image")
const modal=document.getElementById("avatar-modal")
const cropArea=document.getElementById("avatar-crop")
const cropImg=document.getElementById("avatar-crop-image")
const zoom=document.getElementById("avatar-zoom")
const applyBtn=document.getElementById("avatar-apply")
const cancelBtn=document.getElementById("avatar-cancel")
const hidden=document.getElementById("avatar-cropped")
const circle=document.querySelector(".avatar-circle")

let iw=0,ih=0
let scale=1,minScale=1,maxScale=3
let offX=0,offY=0
let drag=false,sx=0,sy=0
let areaRect,circleRect

function measure(){
    areaRect=cropArea.getBoundingClientRect()
    circleRect=circle.getBoundingClientRect()
}
function ensureSingleInput(){
    const all=[...document.querySelectorAll('input[type="file"]#upload-input')]
    if(all.length>1)all.slice(1).forEach(el=>el.remove())
}
ensureSingleInput()
if(label.hasAttribute("for"))label.removeAttribute("for")
label.addEventListener("click",e=>{e.preventDefault();input.click()})
icon.addEventListener("click",e=>{e.stopPropagation();input.click()})

input.addEventListener("change",e=>{
    const f=e.target.files&&e.target.files[0]
    if(!f)return
    const url=URL.createObjectURL(f)
    wrapper.classList.add("loading")
    cropImg.onload=()=>{
        iw=cropImg.naturalWidth
        ih=cropImg.naturalHeight
        measure()
        initTransform()
        zoom.min=minScale.toFixed(2)
        zoom.max="3"
        zoom.value=scale.toFixed(2)
        wrapper.classList.remove("loading")
        modal.classList.remove("hidden")
    }
    cropImg.src=url
})

function initTransform(){
    measure()
    const size=circleRect.width
    const fit=Math.max(size/iw,size/ih)
    minScale=fit
    scale=Math.max(minScale,1)
    const imgW=iw*scale
    const imgH=ih*scale
    const left=circleRect.left-areaRect.left
    const top=circleRect.top-areaRect.top
    offX=left+(circleRect.width-imgW)/2
    offY=top+(circleRect.height-imgH)/2
    apply()
}

function apply(){
    cropImg.style.transform=`translate(${offX}px,${offY}px) scale(${scale})`
}

function clamp(){
    measure()
    const left=circleRect.left-areaRect.left
    const top=circleRect.top-areaRect.top
    const right=left+circleRect.width
    const bottom=top+circleRect.height
    const imgW=iw*scale
    const imgH=ih*scale
    const minX=right-imgW
    const maxX=left
    const minY=bottom-imgH
    const maxY=top
    if(imgW<=circleRect.width){offX=left+(circleRect.width-imgW)/2}else{offX=Math.min(Math.max(offX,minX),maxX)}
    if(imgH<=circleRect.height){offY=top+(circleRect.height-imgH)/2}else{offY=Math.min(Math.max(offY,minY),maxY)}
}

cropArea.addEventListener("pointerdown",e=>{
    drag=true
    cropArea.setPointerCapture(e.pointerId)
    sx=e.clientX
    sy=e.clientY
})
cropArea.addEventListener("pointermove",e=>{
    if(!drag)return
    const dx=e.clientX-sx
    const dy=e.clientY-sy
    sx=e.clientX
    sy=e.clientY
    offX+=dx
    offY+=dy
    clamp()
    apply()
})
cropArea.addEventListener("pointerup",()=>{drag=false})
cropArea.addEventListener("pointercancel",()=>{drag=false})

zoom.addEventListener("input",e=>{
    measure()
    const cx=circleRect.left-areaRect.left+circleRect.width/2
    const cy=circleRect.top-areaRect.top+circleRect.height/2
    const ps=scale
    const ns=parseFloat(e.target.value)
    const imgCx=(cx-offX)/ps
    const imgCy=(cy-offY)/ps
    scale=Math.min(Math.max(ns,minScale),maxScale)
    offX=cx-imgCx*scale
    offY=cy-imgCy*scale
    clamp()
    apply()
})

cancelBtn.addEventListener("click",()=>{
    modal.classList.add("hidden")
    cropImg.src=""
    input.value=""
})

applyBtn.addEventListener("click",()=>{
    measure()
    const size=circleRect.width
    const canvas=document.createElement("canvas")
    const out=512
    canvas.width=out
    canvas.height=out
    const ctx=canvas.getContext("2d")
    ctx.clearRect(0,0,out,out)
    ctx.save()
    ctx.beginPath()
    ctx.arc(out/2,out/2,out/2,0,Math.PI*2)
    ctx.closePath()
    ctx.clip()
    const k=out/size
    const left=circleRect.left-areaRect.left
    const top=circleRect.top-areaRect.top
    const dx=(offX-left)*k
    const dy=(offY-top)*k
    const dw=iw*scale*k
    const dh=ih*scale*k
    ctx.imageSmoothingEnabled=true
    ctx.imageSmoothingQuality="high"
    ctx.drawImage(cropImg,dx,dy,dw,dh)
    ctx.restore()
    const data=canvas.toDataURL("image/png")
    preview.src=data
    preview.style.display="block"
    hidden.value=data
    modal.classList.add("hidden")
    document.getElementById("icon-container").classList.add("icon-hidden")
    label.querySelector(".text").textContent="Modifier l’avatar"
})

document.addEventListener("keydown",e=>{
    if(!modal.classList.contains("hidden")&&e.key==="Escape")cancelBtn.click()
})

window.addEventListener("resize",()=>{
    if(!modal.classList.contains("hidden")&&iw&&ih)initTransform()
})
