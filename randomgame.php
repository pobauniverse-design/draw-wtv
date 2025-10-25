<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Art Contest — Draw & Submit</title>
  <style>
    :root{--bg:#0b0b12;--panel:#0f1724;--accent:#7ee3c5;--muted:#9aa4b2;--glass: rgba(255,255,255,0.04);}*
    {box-sizing:border-box}html,body{height:100%;margin:0;font-family:Inter,ui-sans-serif,system-ui,Segoe UI,Roboto,"Helvetica Neue",Arial}
    body{background:radial-gradient(1200px 500px at 10% 10%, rgba(126,227,197,0.06), transparent),linear-gradient(180deg,#061018 0%, #07111a 60%);color:#e6eef6;padding:18px}
    .app{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr 360px;gap:18px}
    header{grid-column:1/-1;display:flex;align-items:center;gap:12px}
    h1{margin:0;font-size:20px;letter-spacing:0.6px}.subtitle{color:var(--muted);font-size:13px}
    .canvas-wrap{background:linear-gradient(180deg, rgba(255,255,255,0.02), transparent);padding:12px;border-radius:12px;box-shadow:0 6px 24px rgba(4,7,15,0.6)}
    canvas{display:block;width:100%;height:600px;border-radius:8px;background:linear-gradient(180deg,#081016,#071422);cursor:crosshair}
    .toolbar{display:flex;gap:8px;align-items:center;margin-bottom:10px}.tool{background:var(--glass);padding:8px;border-radius:8px;border:1px solid rgba(255,255,255,0.03);display:flex;align-items:center;gap:8px}.tool label{font-size:13px;color:var(--muted)}
    input[type=range]{width:120px}.btn{background:transparent;border:1px solid rgba(255,255,255,0.06);padding:8px 10px;border-radius:8px;color:inherit;cursor:pointer}.btn.primary{background:linear-gradient(90deg,var(--accent),#89d7ff);color:#042524;border:none}
    .side{background:linear-gradient(180deg, rgba(255,255,255,0.02), transparent);padding:12px;border-radius:12px;height:100%}.panel-title{font-weight:600;margin-bottom:8px}.colors{display:flex;gap:6px;flex-wrap:wrap}.swatch{width:28px;height:28px;border-radius:6px;border:2px solid rgba(0,0,0,0.25);cursor:pointer}
    .gallery{display:grid;grid-template-columns:1fr 1fr;gap:8px;max-height:380px;overflow:auto;margin-top:8px}.thumb{background:linear-gradient(180deg,#0b1217,#081018);padding:6px;border-radius:8px;border:1px solid rgba(255,255,255,0.03);display:flex;flex-direction:column;gap:6px}.thumb img{width:100%;height:120px;object-fit:cover;border-radius:6px}.meta{display:flex;justify-content:space-between;align-items:center;font-size:13px;color:var(--muted)}
    .controls-row{display:flex;gap:6px;flex-wrap:wrap}.small{padding:6px 8px;font-size:13px}footer{grid-column:1/-1;margin-top:12px;color:var(--muted);font-size:13px}
    @media (max-width:1000px){.app{grid-template-columns:1fr}.side{order:2}.canvas-wrap{order:1}}
    .ghost{filter:drop-shadow(0 6px 18px rgba(126,227,197,0.06))}.hint{font-size:12px;color:var(--muted)}
  </style>
</head>
<body>
  <div class="app">
    <header>
      <div style="display:flex;align-items:center;gap:12px">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" aria-hidden>
          <rect x="1" y="1" width="22" height="22" rx="6" fill="#041218" stroke="#0b6" stroke-opacity="0.08"/>
          <path d="M7 14s1-3 5-3 5 3 5 3" stroke="#7ee3c5" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div>
        <h1>Art Contest — Draw & Submit</h1>
        <div class="subtitle">Free, browser-based drawing board — save to gallery & vote</div>
      </div>
      <div style="flex:1"></div>
      <div class="hint">Local only demo — works offline in this browser</div>
    </header>

    <section class="canvas-wrap">
      <div class="toolbar">
        <div class="tool"><label for="color">Color</label><input id="color" type="color" value="#7ee3c5" title="Brush color"></div>
        <div class="tool"><label for="size">Size</label><input id="size" type="range" min="1" max="80" value="6"></div>
        <div class="tool"><label for="smoothing">Smooth</label><input id="smoothing" type="range" min="0" max="1" step="0.05" value="0.6" title="Stroke smoothing"></div>
        <div class="tool"><button id="inkMode" class="btn small">Ink Mode</button></div>
        <div class="tool"><button id="eraser" class="btn small">Eraser</button></div>
        <div class="tool"><button id="undo" class="btn small">Undo</button></div>
        <div class="tool"><button id="clear" class="btn small">Clear</button></div>
        <div style="flex:1"></div>
        <div class="tool ghost"><button id="save" class="btn primary">Save & Submit</button></div>
        <div class="tool"><button id="download" class="btn small">Download PNG</button></div>
      </div>

      <canvas id="board"></canvas>
      <div style="display:flex;justify-content:space-between;margin-top:8px;align-items:center">
        <div class="hint">Tip: draw with mouse or touch. Use the Save button to add to the gallery.</div>
        <div class="hint">Strokes stored in memory (undo up to 20). Gallery saved to localStorage.</div>
      </div>
    </section>

    <aside class="side">
      <div class="panel-title">Tools & Colors</div>
      <div class="colors" id="presets"></div>

      <div style="height:12px"></div>
      <div class="panel-title">Gallery</div>
      <div class="hint">Click a thumbnail to open preview / vote.</div>
      <div class="gallery" id="gallery"></div>

      <div style="height:12px"></div>
      <div style="display:flex;gap:6px;align-items:center;margin-top:8px">
        <button id="clearGallery" class="btn small">Clear Gallery</button>
        <button id="exportGallery" class="btn small">Export JSON</button>
      </div>
    </aside>

    <footer>
      This is a single-file demo meant for quick contests or classroom fun. Submissions and votes live in your browser only.
    </footer>
  </div>

  <!-- submission modal -->
  <div id="modal" style="position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(2,6,10,0.6);backdrop-filter:blur(2px)">
    <div style="background:linear-gradient(180deg,#071821,#07121a);padding:16px;border-radius:10px;width:420px;border:1px solid rgba(255,255,255,0.04)">
      <h3 style="margin:0 0 8px 0">Submit your piece</h3>
      <div style="display:flex;flex-direction:column;gap:8px">
        <input id="artistName" placeholder="Your name or nickname" style="padding:8px;border-radius:8px;border:1px solid rgba(255,255,255,0.04);background:transparent;color:inherit">
        <textarea id="desc" placeholder="Optional description" rows="3" style="padding:8px;border-radius:8px;border:1px solid rgba(255,255,255,0.04);background:transparent;color:inherit"></textarea>
        <div style="display:flex;gap:8px;justify-content:flex-end">
          <button id="cancelModal" class="btn small">Cancel</button>
          <button id="confirmModal" class="btn primary">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- preview modal -->
  <div id="preview" style="position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(2,6,10,0.6);backdrop-filter:blur(2px)">
    <div style="background:linear-gradient(180deg,#071821,#07121a);padding:12px;border-radius:10px;width:760px;border:1px solid rgba(255,255,255,0.04)">
      <div style="display:flex;gap:12px">
        <div style="flex:1"><img id="previewImg" style="width:100%;height:520px;object-fit:contain;border-radius:6px"></div>
        <div style="width:200px;display:flex;flex-direction:column;gap:8px">
          <div><strong id="previewName"></strong></div>
          <div id="previewDesc" style="color:var(--muted);font-size:13px"></div>
          <div style="flex:1"></div>
          <div style="display:flex;gap:6px;align-items:center">
            <button id="voteBtn" class="btn small">Vote ❤️</button>
            <div id="voteCount" style="font-weight:700">0</div>
          </div>
          <div style="display:flex;gap:6px;justify-content:flex-end;margin-top:8px">
            <button id="closePreview" class="btn small">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // --- canvas & improved smoothing / inked lines ---
    const canvas = document.getElementById('board');
    const ctx = canvas.getContext('2d');
    let drawing=false; let brushColor=document.getElementById('color').value; let brushSize=+document.getElementById('size').value; let isEraser=false; let inkMode=false;
    const UNDO_LIMIT=30; let undoStack=[];
    let strokes=[]; // each stroke: {points:[{x,y,t}], size, color, mode}

    function fitCanvas(){
      const rect = canvas.getBoundingClientRect();
      const dpr = window.devicePixelRatio || 1;
      canvas.width = Math.floor(rect.width * dpr);
      canvas.height = Math.floor(rect.height * dpr);
      canvas.style.width = rect.width + 'px';
      canvas.style.height = rect.height + 'px';
      // use CSS pixels for drawing math; reset transform
      ctx.setTransform(dpr,0,0,dpr,0,0);
      redraw();
    }

    // smoothing config
    const smoothingInput = document.getElementById('smoothing');

    function pushUndo(){
      try{
        undoStack.push(JSON.stringify(strokes));
        if(undoStack.length>UNDO_LIMIT) undoStack.shift();
      }catch(e){/*ignore*/}
    }
    function undo(){ if(undoStack.length<=1) {strokes=[]; redraw(); return} undoStack.pop(); const s=undoStack.pop(); strokes = s ? JSON.parse(s) : []; redraw(); pushUndo(); }

    // draw using quadratic smoothing + optional velocity width for a more 'inked' look
    function redraw(){
      // clear using CSS pixels
      ctx.save();
      ctx.setTransform(1,0,0,1,0,0);
      ctx.clearRect(0,0,canvas.width,canvas.height);
      ctx.restore();
      // background
      ctx.fillStyle = '#071421'; ctx.fillRect(0,0,canvas.width,canvas.height);

      strokes.forEach(s=>{
        ctx.lineJoin='round'; ctx.lineCap='round';
        if(s.mode==='eraser') ctx.globalCompositeOperation='destination-out'; else ctx.globalCompositeOperation='source-over';
        if(inkMode && s.mode!=='eraser') {
          // draw a filled path for 'ink' look (variable thickness)
          drawInkedStroke(s);
        } else {
          // classic stroked line with smoothing
          ctx.beginPath();
          const pts = s.points;
          if(pts.length===1){ ctx.lineWidth = s.size; ctx.strokeStyle = s.color; ctx.beginPath(); ctx.moveTo(pts[0].x, pts[0].y); ctx.lineTo(pts[0].x+0.1, pts[0].y+0.1); ctx.stroke(); }
          else{
            ctx.lineWidth = s.size; ctx.strokeStyle = s.color;
            ctx.moveTo(pts[0].x, pts[0].y);
            for(let i=1;i<pts.length-1;i++){
              const c = pts[i]; const next = pts[i+1];
              const mx = (c.x + next.x)/2; const my = (c.y + next.y)/2;
              ctx.quadraticCurveTo(c.x, c.y, mx, my);
            }
            // last segment
            const last = pts[pts.length-1]; ctx.lineTo(last.x, last.y);
            ctx.stroke();
          }
        }
        ctx.globalCompositeOperation='source-over';
      });
    }

    function drawInkedStroke(s){
      // create polygon around the smoothed spine by offsetting normals using local width
      const pts = s.points;
      if(pts.length<2){ ctx.fillStyle = s.color; ctx.beginPath(); ctx.arc(pts[0].x, pts[0].y, s.size/2, 0, Math.PI*2); ctx.fill(); return }
      const spine = computeSmoothSpine(pts);
      // build left and right offset points
      const left = [], right = [];
      for(let i=0;i<spine.length;i++){
        const p = spine[i]; const w = Math.max(1, s.size * (1 + (p.v? (1 - Math.min(1, p.v/200)) : 0)));
        const nx = p.nx || 0, ny = p.ny || 0;
        left.push({x: p.x - nx * w/2, y: p.y - ny * w/2});
        right.push({x: p.x + nx * w/2, y: p.y + ny * w/2});
      }
      // draw polygon
      ctx.beginPath(); ctx.moveTo(left[0].x, left[0].y);
      for(let i=1;i<left.length;i++) ctx.lineTo(left[i].x, left[i].y);
      for(let i=right.length-1;i>=0;i--) ctx.lineTo(right[i].x, right[i].y);
      ctx.closePath(); ctx.fillStyle = s.color; ctx.fill();
    }

    function computeSmoothSpine(pts){
      // compute midpoints and normals + simple velocity estimate
      const spine=[];
      for(let i=0;i<pts.length;i++){
        const p = pts[i]; const prev = pts[i-1]||p; const next = pts[i+1]||p;
        // tangent
        const tx = next.x - prev.x; const ty = next.y - prev.y;
        const len = Math.hypot(tx,ty) || 1;
        const nx = -ty/len; const ny = tx/len; // normal
        // velocity (speed) estimate using time differences if available
        const v = (()=>{ if(p.t && prev.t){ const dt = p.t - prev.t; if(dt>0) return Math.hypot(p.x-prev.x,p.y-prev.y)/dt; } return 0 })();
        spine.push({x:p.x,y:p.y,nx,ny,v});
      }
      return spine;
    }

    // pointer helpers
    function now(){ return performance.now(); }
    function getPointer(e){ const r = canvas.getBoundingClientRect(); const clientX = e.touches ? e.touches[0].clientX : e.clientX; const clientY = e.touches ? e.touches[0].clientY : e.clientY; return {x: clientX - r.left, y: clientY - r.top}; }

    function startStroke(x,y){ drawing=true; const t=now(); const s={points:[{x,y,t}], size:brushSize, color:brushColor, mode:isEraser?'eraser':'brush'}; strokes.push(s); pushUndo(); redraw(); }
    function extendStroke(x,y){ if(!drawing) return; const s = strokes[strokes.length-1]; const last = s.points[s.points.length-1]; const t=now(); // simple smoothing by interpolating toward new point depending on smoothing factor
      const smoothing = +smoothingInput.value;
      // add point with slight interpolation to reduce jitter
      const nx = last.x + (x - last.x) * (1 - smoothing);
      const ny = last.y + (y - last.y) * (1 - smoothing);
      s.points.push({x:nx,y:ny,t}); redraw(); }
    function endStroke(){ drawing=false; }

    // events
    canvas.addEventListener('pointerdown', (ev)=>{ canvas.setPointerCapture(ev.pointerId); const p=getPointer(ev); startStroke(p.x,p.y); });
    canvas.addEventListener('pointermove', (ev)=>{ if(drawing){ const p=getPointer(ev); extendStroke(p.x,p.y); } });
    canvas.addEventListener('pointerup', (ev)=>{ try{canvas.releasePointerCapture(ev.pointerId);}catch(e){} endStroke(); });
    canvas.addEventListener('pointercancel', ()=>endStroke());
    window.addEventListener('resize', ()=>fitCanvas());

    // tools binding
    document.getElementById('color').addEventListener('input', (e)=>{ brushColor=e.target.value; isEraser=false; document.getElementById('eraser').classList.remove('active'); });
    document.getElementById('size').addEventListener('input', (e)=>{ brushSize=+e.target.value });
    document.getElementById('eraser').addEventListener('click', ()=>{ isEraser = !isEraser; document.getElementById('eraser').classList.toggle('active'); });
    document.getElementById('inkMode').addEventListener('click', ()=>{ inkMode = !inkMode; document.getElementById('inkMode').classList.toggle('active'); document.getElementById('inkMode').textContent = inkMode ? 'Ink: ON' : 'Ink Mode'; redraw(); });
    document.getElementById('clear').addEventListener('click', ()=>{ if(confirm('Clear the board? This cannot be undone.')){ strokes=[]; pushUndo(); redraw(); }});
    document.getElementById('undo').addEventListener('click', ()=>undo());

    // export
    function exportCanvasDataURL(){ const outW = 1200, outH = Math.max(600, Math.round((canvas.height/canvas.width)*1200)); const temp = document.createElement('canvas'); temp.width = outW; temp.height = outH; const tctx = temp.getContext('2d'); tctx.fillStyle='#071421'; tctx.fillRect(0,0,outW,outH);
      // naive scaling: draw the visible canvas scaled
      tctx.drawImage(canvas, 0, 0, outW, outH);
      return temp.toDataURL('image/png'); }
    document.getElementById('download').addEventListener('click', ()=>{ const a=document.createElement('a'); a.href=exportCanvasDataURL(); a.download='drawing.png'; a.click(); });

    // gallery (same as before)
    const STORAGE_KEY='art-contest-gallery-v1';
    function loadGallery(){ const raw = localStorage.getItem(STORAGE_KEY); if(!raw) return []; try{return JSON.parse(raw);}catch(e){return []} }
    function saveGallery(arr){ localStorage.setItem(STORAGE_KEY, JSON.stringify(arr)) }
    function renderGallery(){ const gallery=document.getElementById('gallery'); gallery.innerHTML=''; const arr = loadGallery(); arr.slice().reverse().forEach(item=>{ const div=document.createElement('div'); div.className='thumb'; div.innerHTML = `<img src="${item.data}"><div class="meta"><div style='font-size:13px'>${escapeHtml(item.name||'Anonymous')}</div><div style='font-weight:700'>${item.votes||0} ❤️</div></div>`; div.addEventListener('click', ()=>openPreview(item)); gallery.appendChild(div); }); }
    function escapeHtml(s){ return (s||'').replace(/[&<>\"']/g, c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"})[c]) }

    document.getElementById('save').addEventListener('click', ()=>{ showModal(); });
    document.getElementById('clearGallery').addEventListener('click', ()=>{ if(confirm('Clear all gallery submissions in this browser?')){ localStorage.removeItem(STORAGE_KEY); renderGallery(); }});
    document.getElementById('exportGallery').addEventListener('click', ()=>{ const data = localStorage.getItem(STORAGE_KEY) || '[]'; const a=document.createElement('a'); const blob=new Blob([data],{type:'application/json'}); a.href=URL.createObjectURL(blob); a.download='gallery.json'; a.click(); });

    // modal logic
    const modal=document.getElementById('modal'); const preview=document.getElementById('preview'); const previewImg=document.getElementById('previewImg'); const previewName=document.getElementById('previewName'); const previewDesc=document.getElementById('previewDesc'); const voteCount=document.getElementById('voteCount'); let currentPreviewItem=null;
    function showModal(){ modal.style.display='flex'; document.getElementById('artistName').value=''; document.getElementById('desc').value=''; }
    document.getElementById('cancelModal').addEventListener('click', ()=>{ modal.style.display='none' });
    document.getElementById('confirmModal').addEventListener('click', ()=>{ const name=document.getElementById('artistName').value.trim() || 'Anonymous'; const desc=document.getElementById('desc').value.trim(); const data = exportCanvasDataURL(); const items = loadGallery(); items.push({id:Date.now(), name, desc, data, votes:0}); saveGallery(items); modal.style.display='none'; renderGallery(); alert('Saved to gallery!'); });
    function openPreview(item){ currentPreviewItem=item; previewImg.src=item.data; previewName.textContent=item.name||'Anonymous'; previewDesc.textContent=item.desc||''; voteCount.textContent=item.votes||0; preview.style.display='flex'; }
    document.getElementById('closePreview').addEventListener('click', ()=>{ preview.style.display='none' });
    document.getElementById('voteBtn').addEventListener('click', ()=>{ if(!currentPreviewItem) return; const key = 'voted-' + currentPreviewItem.id; if(sessionStorage.getItem(key)){ alert('You already voted for this one in this session.'); return } const items=loadGallery(); const it = items.find(i=>i.id===currentPreviewItem.id); if(it){ it.votes=(it.votes||0)+1; saveGallery(items); sessionStorage.setItem(key,'1'); voteCount.textContent=it.votes; renderGallery(); } });

    // color presets
    const presets=['#7ee3c5','#89d7ff','#ffd97e','#ff89b0','#b48cff','#ffffff','#000000','#6ef0a3']; const presetsWrap=document.getElementById('presets'); presets.forEach(c=>{ const s=document.createElement('div'); s.className='swatch'; s.style.background=c; s.title=c; s.addEventListener('click', ()=>{ document.getElementById('color').value=c; brushColor=c; isEraser=false; }); presetsWrap.appendChild(s); });

    // init
    (function init(){ fitCanvas(); renderGallery(); pushUndo(); })();

    // Helpful note for you:
    // I interpreted your request "make the lines ingf" as wanting smoother, more ink-like lines.
    // Changes made: smoothing via quadratic curves, optional Ink Mode (produces filled variable-width strokes), and a smoothing slider.

  </script>
</body>
</html>
