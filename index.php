<?php
require_once __DIR__ . '/api/helpers.php';
$db = cl_db();
$seo = $db->query('SELECT * FROM seo_settings WHERE id=1')->fetch() ?: [];
$title = htmlspecialchars($seo['title'] ?? 'Cyber Legend');
$desc = htmlspecialchars($seo['description'] ?? '');
$keywords = htmlspecialchars($seo['keywords'] ?? '');
$ogTitle = htmlspecialchars($seo['og_title'] ?: ($seo['title'] ?? 'Cyber Legend'));
$ogDesc = htmlspecialchars($seo['og_description'] ?: ($seo['description'] ?? ''));
$ogImage = htmlspecialchars($seo['og_image'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?></title>
<meta name="description" content="<?= $desc ?>">
<meta name="keywords" content="<?= $keywords ?>">
<meta property="og:title" content="<?= $ogTitle ?>">
<meta property="og:description" content="<?= $ogDesc ?>">
<?php if ($ogImage): ?><meta property="og:image" content="<?= $ogImage ?>"><?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--bg:#050810;--card:rgba(255,255,255,0.045);--card-b:rgba(120,170,255,0.18);--primary:#3b82f6;--secondary:#22d3ee;--text:#e8eefb;--muted:#8ea0c2;--glow:0.55;--radius:16px;}
*{box-sizing:border-box;margin:0;padding:0;}
body{background:var(--bg);color:var(--text);font-family:'Inter',sans-serif;overflow-x:hidden;}
h1,h2,h3{font-family:'Space Grotesk',sans-serif;}
img{max-width:100%;display:block;}
a{color:inherit;text-decoration:none;}
.bgfx{position:fixed;inset:0;z-index:-1;background:radial-gradient(600px circle at 20% 20%,rgba(59,130,246,calc(var(--glow)*0.18)),transparent 60%),radial-gradient(700px circle at 80% 70%,rgba(34,211,238,calc(var(--glow)*0.14)),transparent 60%),var(--bg);}
#loader{position:fixed;inset:0;background:var(--bg);z-index:999;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:18px;transition:opacity .5s ease;}
.loader-ring{width:64px;height:64px;border-radius:50%;border:3px solid rgba(59,130,246,0.15);border-top-color:var(--primary);animation:spin 1s linear infinite;box-shadow:0 0 calc(20px*var(--glow)) rgba(59,130,246,var(--glow));}
@keyframes spin{to{transform:rotate(360deg)}}
#loader p{color:var(--muted);font-size:.9rem;}
.hero{min-height:100svh;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:40px 20px;opacity:0;transform:translateY(16px);transition:opacity .8s ease,transform .8s ease;}
.hero.in{opacity:1;transform:none;}
.dp-wrap{width:132px;height:132px;margin-bottom:22px;}
.dp{width:132px;height:132px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);box-shadow:0 0 calc(30px*var(--glow)) rgba(59,130,246,var(--glow)),0 0 calc(60px*var(--glow)) rgba(34,211,238,calc(var(--glow)*0.5));}
.namewrap{display:flex;align-items:center;gap:8px;font-size:1.7rem;font-weight:700;}
.badge{width:20px;height:20px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;font-size:11px;box-shadow:0 0 12px rgba(59,130,246,var(--glow));}
.tagline{color:var(--muted);margin-top:10px;font-size:.98rem;max-width:420px;}
.section{max-width:880px;margin:0 auto;padding:60px 22px;opacity:0;transform:translateY(24px);transition:opacity .6s ease,transform .6s ease;}
.section.in{opacity:1;transform:none;}
.section h2{font-size:1.5rem;margin-bottom:16px;}
.card{background:var(--card);border:1px solid var(--card-b);border-radius:var(--radius);padding:22px;backdrop-filter:blur(10px);}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:12px;margin-top:18px;}
.stat{background:var(--card);border:1px solid var(--card-b);border-radius:12px;padding:16px;text-align:center;}
.stat b{display:block;font-size:1.3rem;font-family:'Space Grotesk',sans-serif;}
.stat span{color:var(--muted);font-size:.78rem;}
.sec-grid{display:grid;gap:16px;}
.sec-card{display:flex;gap:16px;align-items:flex-start;}
.sec-card img{width:64px;height:64px;border-radius:10px;object-fit:cover;flex-shrink:0;}
.sec-card .icon{width:64px;height:64px;border-radius:10px;background:rgba(59,130,246,.12);display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0;}
.sec-card h3{font-size:1.05rem;margin-bottom:6px;}
.sec-card p{color:var(--muted);font-size:.9rem;line-height:1.5;}
.sec-btn{display:inline-block;margin-top:10px;padding:7px 16px;border-radius:999px;background:var(--primary);font-size:.82rem;font-weight:600;}
.socials{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;padding:10px 22px 0;}
.soc-btn{padding:10px 18px;border-radius:999px;border:1px solid var(--card-b);background:var(--card);font-size:.85rem;font-weight:600;transition:transform .15s ease,box-shadow .15s ease;}
.soc-btn:hover{transform:translateY(-2px);box-shadow:0 4px 18px rgba(59,130,246,.25);}
footer{text-align:center;padding:50px 20px 30px;color:var(--muted);font-size:.82rem;}
.footer-socials{display:flex;gap:14px;justify-content:center;margin:14px 0;}
#toTop{margin-top:18px;padding:8px 16px;border-radius:999px;border:1px solid var(--card-b);background:var(--card);color:var(--text);font-size:.8rem;}
.pill{font-size:.75rem;color:var(--muted);}
</style>
</head>
<body>
<div class="bgfx"></div>
<div id="loader"><div class="loader-ring"></div><p id="loaderText">Loading…</p></div>
<section class="hero" id="hero">
  <div class="dp-wrap"><img class="dp" id="dpImg" src="" alt="profile"></div>
  <div class="namewrap"><span id="nameOut">Cyber Legend</span><span class="badge" id="badgeOut">✓</span></div>
  <p class="tagline" id="taglineOut"></p>
</section>
<section class="section" id="aboutSec">
  <h2>About</h2>
  <div class="card"><p id="aboutOut" style="color:var(--muted);line-height:1.6;"></p></div>
  <div class="stats" id="statsOut"></div>
</section>
<section class="section" id="sectionsSec">
  <h2>Highlights</h2>
  <div class="sec-grid" id="sectionsOut"></div>
</section>
<div class="socials" id="socialsOut"></div>
<footer>
  <div style="font-weight:700;color:var(--text);font-family:'Space Grotesk',sans-serif;">CYBER LEGEND</div>
  <div class="footer-socials" id="footerSocialsOut"></div>
  <div id="copyOut">© <?= date('Y') ?> Cyber Legend. All rights reserved.</div>
  <button id="toTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">Back to top</button>
  <div style="margin-top:16px;"><a href="admin/" class="pill">Admin →</a></div>
</footer>
<script>
const SOC_ICON={WhatsApp:'💬',Instagram:'📷',Telegram:'✈️',Facebook:'📘',YouTube:'▶️',TikTok:'🎵','X/Twitter':'✕',Custom:'🔗'};
function esc(s){return (s||'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}
function track(type){ fetch('api/analytics.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({type})}); }

fetch('api/public_data.php').then(r=>r.json()).then(d=>{
  if(!d.ok) return;
  const p=d.profile||{}, th=d.theme||{}, l=d.loader||{};
  document.documentElement.style.setProperty('--primary', th.primary_color||'#3b82f6');
  document.documentElement.style.setProperty('--secondary', th.secondary_color||'#22d3ee');
  document.documentElement.style.setProperty('--bg', th.bg_color||'#050810');
  document.documentElement.style.setProperty('--glow', th.glow||0.55);

  document.getElementById('nameOut').textContent=p.name||'Cyber Legend';
  document.getElementById('badgeOut').style.display=(p.badge==1)?'flex':'none';
  document.getElementById('taglineOut').textContent=p.tagline||'';
  document.getElementById('aboutOut').textContent=p.about||'';
  document.getElementById('dpImg').src=p.dp_path?p.dp_path:'https://api.dicebear.com/7.x/bottts/svg?seed=cyberlegend';

  document.getElementById('statsOut').innerHTML=(p.stats||[]).map(s=>`<div class="stat"><b>${esc(s.value)}</b><span>${esc(s.label)}</span></div>`).join('');

  document.getElementById('sectionsOut').innerHTML=(d.sections||[]).map(s=>`
    <div class="card sec-card">
      ${s.image_path?`<img src="${s.image_path}">`:`<div class="icon">${s.icon||'⚡'}</div>`}
      <div><h3>${esc(s.title)}</h3><p>${esc(s.description)}</p>${s.btn_text?`<a class="sec-btn" href="${s.btn_url||'#'}" target="_blank" onclick="track('click')">${esc(s.btn_text)}</a>`:''}</div>
    </div>`).join('') || '<p class="pill">No sections yet.</p>';

  const socHtml=(d.socials||[]).map(s=>`<a class="soc-btn" href="${s.url}" target="_blank" onclick="track('click')">${SOC_ICON[s.type]||'🔗'} ${esc(s.type)}</a>`).join('');
  document.getElementById('socialsOut').innerHTML=socHtml;
  document.getElementById('footerSocialsOut').innerHTML=(d.socials||[]).map(s=>`<a href="${s.url}" target="_blank" onclick="track('click')">${SOC_ICON[s.type]||'🔗'}</a>`).join('');
  document.getElementById('copyOut').textContent=`© ${new Date().getFullYear()} ${p.name||'Cyber Legend'}. All rights reserved.`;

  document.getElementById('loaderText').textContent=l.loader_text||'Loading…';
  const showHero=()=>{ document.getElementById('loader').style.opacity='0'; setTimeout(()=>document.getElementById('loader').style.display='none',500); document.getElementById('hero').classList.add('in'); };
  if(l.enabled==1){ setTimeout(showHero, l.duration_ms||2000); } else { document.getElementById('loader').style.display='none'; showHero(); }
  track('pageview');
}).catch(()=>{ document.getElementById('loaderText').textContent='Could not load site data. Check your database connection.'; });

track('visit');
new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('in');}),{threshold:0.15}).observe(document.getElementById('aboutSec'));
document.querySelectorAll('.section').forEach(s=>new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)e.target.classList.add('in');}),{threshold:0.15}).observe(s));
</script>
</body>
</html>
