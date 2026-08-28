<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Yeni Hasta Kaydı</title>
  <style>
    :root { --brand:#107c41; --brand-dark:#0b5c30; --ink:#1f2937; --muted:#5f6b7a; --line:#d7dde6; --canvas:#f3f5f7; --surface:#fff; --focus:#0067c0; }
    * { box-sizing:border-box; } body { margin:0; background:var(--canvas); color:var(--ink); font:14px/1.4 "Segoe UI", system-ui, sans-serif; }
    .app { max-width:1440px; margin:auto; min-height:100vh; display:grid; grid-template-columns:240px 1fr; }
    .sidebar { background:#fff; border-right:1px solid var(--line); padding:20px 12px; }
    .brand { display:flex; gap:10px; align-items:center; padding:10px 12px 24px; font-size:18px; font-weight:650; }
    .brand-mark,.section-icon { display:grid; place-items:center; width:32px; height:32px; border-radius:8px; background:#e7f4ec; color:var(--brand); font-weight:800; }
    .nav-label { color:var(--muted); font-size:12px; padding:10px 12px 6px; } .nav a { display:flex; gap:12px; align-items:center; border-radius:6px; color:#344054; padding:11px 12px; text-decoration:none; margin:2px 0; } .nav a.active,.nav a:hover { background:#e8f3eb; color:var(--brand-dark); font-weight:600; }
    main { padding:32px; } .topbar { display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:24px; } h1 { font-size:28px; margin:0 0 4px; letter-spacing:-.3px; } .subtitle { color:var(--muted); margin:0; }
    .actions,.footer-actions { display:flex; gap:10px; } button { border:1px solid var(--line); background:#fff; border-radius:6px; padding:9px 15px; font:inherit; font-weight:600; color:#344054; cursor:pointer; } button:hover { background:#f7f9fb; } .primary { border-color:var(--brand); background:var(--brand); color:#fff; } .primary:hover { background:var(--brand-dark); }
    form { display:grid; grid-template-columns:minmax(360px,1fr) minmax(360px,1fr); gap:18px; } .card { background:var(--surface); border:1px solid #e3e7ed; border-radius:10px; box-shadow:0 1px 2px rgba(16,24,40,.04); padding:22px; } .card.primary-card { grid-row:span 2; }
    .card-heading { display:flex; align-items:center; gap:10px; font-size:17px; font-weight:650; margin:0 0 20px; padding-bottom:14px; border-bottom:1px solid var(--line); } .section-icon { width:28px; height:28px; border-radius:50%; font-size:13px; }
    .field-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; } .field { display:grid; gap:6px; } .field.full { grid-column:1/-1; } label,legend { color:#344054; font-size:13px; font-weight:600; } .required { color:#c62828; }
    input,select,textarea { width:100%; min-height:40px; border:1px solid #b9c3d0; background:#fff; border-radius:5px; padding:9px 11px; color:var(--ink); font:inherit; outline:none; } textarea { min-height:84px; resize:vertical; } input:focus,select:focus,textarea:focus { border-color:var(--focus); box-shadow:0 0 0 2px rgba(0,103,192,.15); }
    .rating { display:flex; gap:4px; } .rating button { border:0; padding:0; background:transparent; color:#98a2b3; font-size:26px; line-height:1; } .rating button.selected { color:#f2a900; } fieldset { border:0; padding:0; margin:0; } .radios { display:flex; gap:20px; padding-top:7px; } .radios label { display:flex; gap:7px; align-items:center; font-weight:400; } .radios input { width:16px; min-height:auto; accent-color:var(--brand); }
    .form-footer { grid-column:1/-1; position:sticky; bottom:12px; display:flex; align-items:center; justify-content:space-between; gap:16px; padding:16px 20px; background:rgba(255,255,255,.96); backdrop-filter:blur(10px); border:1px solid var(--line); border-radius:10px; box-shadow:0 8px 24px rgba(16,24,40,.1); } .hint { color:var(--muted); font-size:13px; }
    @media (max-width:900px) { .app { grid-template-columns:1fr; } .sidebar { display:none; } main { padding:20px; } form { grid-template-columns:1fr; } .card.primary-card { grid-row:auto; } } @media (max-width:560px) { main { padding:14px; } .topbar,.form-footer { align-items:flex-start; flex-direction:column; } .field-grid { grid-template-columns:1fr; } .field.full { grid-column:auto; } .footer-actions { width:100%; } .footer-actions button { flex:1; } }
    /* Windows XP inspired presentation layer */
    body { background:#3a6ea5; font-family:Tahoma, "Segoe UI", sans-serif; }
    .app { max-width:1500px; background:#ece9d8; border:1px solid #003c74; box-shadow:0 0 0 1px #fff, 0 12px 35px rgba(0,0,0,.35); }
    .sidebar { background:linear-gradient(180deg,#245edb 0,#3f8cf3 14%,#2b6ed1 100%); border-right:2px solid #fff; color:#fff; }
    .brand { margin:-8px -4px 18px; padding:13px 12px; color:#fff; background:linear-gradient(180deg,#0a58d8,#0744a5); border:1px solid #003c74; border-radius:7px 7px 0 0; text-shadow:1px 1px #003c74; }
    .brand-mark { background:linear-gradient(#54c85c,#159229); color:#fff; border:1px solid #fff; border-radius:5px; box-shadow:inset 1px 1px rgba(255,255,255,.6); }
    .nav-label { color:#dceaff; font-weight:bold; text-shadow:1px 1px #124797; }
    .nav a { color:#fff; border:1px solid transparent; text-shadow:1px 1px #124797; }
    .nav a:hover,.nav a.active { color:#003399; background:linear-gradient(90deg,#fff,#d6e8ff); border-color:#fff; text-shadow:none; }
    main { background:linear-gradient(135deg,#f5f3e9 0,#dfe9f4 100%); }
    .topbar { padding:12px 16px; background:linear-gradient(180deg,#3c8cf3 0,#1764d5 12%,#0a4eae 100%); border:1px solid #003c74; border-radius:7px 7px 0 0; box-shadow:inset 1px 1px rgba(255,255,255,.65); }
    h1 { color:#fff; font-size:22px; text-shadow:1px 1px #003c74; } .subtitle { color:#e7f1ff; }
    .card { background:#f5f4ea; border:1px solid #7f9db9; border-radius:3px; box-shadow:inset 1px 1px #fff, 2px 2px 4px rgba(0,0,0,.15); }
    .card-heading { margin:-22px -22px 20px; padding:8px 12px; color:#fff; background:linear-gradient(180deg,#2f83ed,#0a55c5); border-bottom:1px solid #003c74; font-size:15px; text-shadow:1px 1px #003c74; }
    .section-icon { background:#fff; color:#0755bd; border:1px solid #003c74; }
    input,select,textarea { border:1px solid #7f9db9; border-radius:0; box-shadow:inset 1px 1px 2px rgba(0,0,0,.16); font-family:Tahoma,sans-serif; }
    input:focus,select:focus,textarea:focus { border-color:#003c74; box-shadow:0 0 0 1px #ffcc66,inset 1px 1px 2px rgba(0,0,0,.15); }
    button { color:#111; background:linear-gradient(#fff,#ece9d8); border:1px solid #003c74; border-radius:3px; box-shadow:inset 1px 1px #fff,1px 1px #7f9db9; font-family:Tahoma,sans-serif; }
    button:hover { background:linear-gradient(#fff7d6,#ffd27a); }
    .primary { color:#fff; background:linear-gradient(#62d169,#159229); border-color:#09621a; text-shadow:1px 1px #075612; }
    .primary:hover { background:linear-gradient(#7be57d,#21a434); }
    .form-footer { background:#ece9d8; border-color:#7f9db9; border-radius:3px; box-shadow:inset 1px 1px #fff,2px 2px 4px rgba(0,0,0,.18); }
    /* Compact desktop-application density */
    main { padding:16px 20px; }
    .topbar { margin-bottom:12px; padding:8px 10px; }
    h1 { font-size:18px; } .subtitle { font-size:11px; }
    form { max-width:1120px; grid-template-columns:1.05fr .95fr; gap:10px; }
    .card { padding:13px; }
    .card-heading { margin:-13px -13px 12px; padding:5px 8px; font-size:13px; }
    .section-icon { width:20px; height:20px; font-size:10px; }
    .field-grid { gap:8px 10px; }
    .field { gap:3px; }
    label,legend { font-size:11px; }
    input,select,textarea { min-height:28px; height:28px; padding:3px 7px; font-size:12px; }
    textarea { height:48px; min-height:48px; }
    .radios { padding-top:3px; gap:14px; }
    .radios input { height:13px; width:13px; }
    .rating button { font-size:19px; }
    button { padding:5px 11px; font-size:12px; }
    .form-footer { padding:8px 10px; bottom:6px; }
    .hint { font-size:11px; }
    .field-grid { grid-template-columns:230px 230px; justify-content:start; }
    .field { width:230px; }
    .field.full { width:470px; max-width:100%; }
    .field.full input,.field.full select,.field.full textarea { width:470px; max-width:100%; }
    .radios,.rating { width:max-content; }
    @media (max-width:1100px) { .field-grid { grid-template-columns:minmax(0,1fr); } .field,.field.full { width:100%; } .field.full { grid-column:auto; } input,select,textarea,.field.full input,.field.full select,.field.full textarea { width:100%; } }
    /* Four compact fields per desktop row */
    form { max-width:1040px; grid-template-columns:1fr; }
    .card.primary-card { grid-row:auto; }
    .field-grid { grid-template-columns:repeat(4,210px); gap:8px 12px; }
    .field { width:210px; }
    .field.full { grid-column:span 2; width:432px; }
    .field.full input,.field.full select,.field.full textarea { width:432px; }
    @media (max-width:1050px) { .field-grid { grid-template-columns:repeat(2,210px); } }
    @media (max-width:560px) { .field-grid { grid-template-columns:1fr; } .field,.field.full { width:100%; grid-column:auto; } input,select,textarea,.field.full input,.field.full select,.field.full textarea { width:100%; } }
    /* Browser-based XP desktop shell */
    html,body { width:100%; height:100%; overflow:hidden; }
    [hidden] { display:none !important; }
    body { background:radial-gradient(ellipse at 52% 48%,#082c67 0%,#041f54 58%,#011238 100%); }
    body:before { display:none; }
    .login-screen { position:fixed; z-index:20000; inset:0; display:flex; align-items:center; justify-content:center; padding:20px; background:radial-gradient(circle at 50% 42%,#245849 0%,#123c32 52%,#09261f 100%); transition:opacity .32s ease,visibility .32s; }
    .login-screen.hidden { opacity:0; visibility:hidden; pointer-events:none; }
    .login-panel { width:370px; padding:25px 28px 22px; background:rgba(255,249,237,.98); border:1px solid #e3c66e; border-radius:8px; box-shadow:0 18px 55px rgba(0,0,0,.42),0 0 0 1px rgba(255,255,255,.16); }
    .login-logo { display:block; width:210px; height:auto; margin:0 auto 18px; }
    .login-title { margin:0 0 4px; color:#2b261c; text-align:center; font:bold 19px Tahoma; } .login-subtitle { margin:0 0 20px; color:#75663f; text-align:center; font-size:11px; }
    .login-field { display:grid; gap:5px; margin-bottom:12px; } .login-field label { color:#263c31; font-size:11px; }
    .login-field input { height:34px; padding:7px 9px; border:1px solid #cbb982; border-radius:4px; background:#fffdf8; font-size:12px; box-shadow:inset 1px 1px 2px rgba(0,0,0,.08); }
    .login-field input:focus { border-color:#b18424; box-shadow:0 0 0 2px rgba(177,132,36,.2); }
    .login-options { display:flex; justify-content:space-between; align-items:center; margin:2px 0 16px; color:#6f6243; font-size:10px; } .login-options label { display:flex; align-items:center; gap:6px; } .login-options input { width:13px; height:13px; min-height:0; accent-color:#b18424; }
    .login-submit { width:100%; height:35px; color:#fff; border-color:#7a5713; border-radius:4px; background:linear-gradient(#e6c665,#ad7e1f); text-shadow:1px 1px #6a490d; font:bold 12px Tahoma; }
    .login-submit:hover { background:linear-gradient(#f1d77d,#c4932d); }
    .login-error { min-height:17px; margin:9px 0 0; color:#b42318; text-align:center; font-size:10px; }
    .desktop-status { display:none; }
    .desktop-status img { display:block; width:100%; max-height:76px; object-fit:contain; }
    .desktop-icons { display:none; }
    .desktop-icon { position:absolute; width:90px; border:0; background:transparent; color:#fff; box-shadow:none; text-shadow:1px 1px 2px #003c74; padding:0; text-align:center; font:12px Tahoma,sans-serif; pointer-events:auto; touch-action:none; cursor:default; }
    .desktop-icon:nth-child(1){left:24px;top:82px}.desktop-icon:nth-child(2){left:138px;top:82px}.desktop-icon:nth-child(3){left:24px;top:178px}.desktop-icon:nth-child(4){left:138px;top:178px}
    .desktop-icon.dragging { opacity:.8; cursor:move; z-index:4; background:transparent; outline:none; }
    .desktop-icon:hover { background:rgba(49,106,197,.45); outline:1px dotted #fff; }
    .desktop-icon .ico { display:block; margin:0 auto 5px; width:54px; height:48px; font-size:38px; line-height:48px; filter:drop-shadow(2px 3px 1px rgba(0,0,0,.35)); }
    .desktop-icon .ico svg { display:block; width:54px; height:48px; }
    .app { position:fixed; z-index:5; top:8vh; right:4vw; width:min(1080px,76vw); height:82vh; min-height:0; margin:0; display:block; overflow:hidden; background:#d8e5f5; border:1px solid #003c74; border-radius:5px 5px 0 0; box-shadow:1px 1px 0 #fff inset,3px 5px 10px rgba(0,0,0,.35); }
    .sidebar { display:none; }
    main { height:100%; padding:0 7px 9px; overflow:auto; background:#dfe9f6; }
    .topbar { position:sticky; z-index:10; top:0; height:27px; margin:0 -7px 6px; padding:3px 5px 3px 8px; border-radius:4px 4px 0 0; background:linear-gradient(180deg,#4b96f5 0,#1464d2 18%,#0751bd 72%,#2b7ae5 100%); }
    .topbar h1 { font-size:12px; line-height:20px; } .topbar .subtitle { display:none; }
    .topbar .actions { gap:3px; }
    .topbar .actions button { width:22px; height:20px; padding:0; overflow:hidden; font-size:0; color:#fff; border-color:#fff; background:linear-gradient(#77aaf2,#226ed0); }
    .topbar .actions button:first-child:after { content:"×"; font:bold 15px Tahoma; }
    .topbar .actions .primary:after { content:"✓"; font:bold 12px Tahoma; }
    form { max-width:none; grid-template-columns:1fr; gap:5px; }
    .card { padding:10px 8px 7px; border:1px solid #7f9db9; background:#e5edf8; box-shadow:inset 1px 1px #fff; }
    .card-heading { margin:-10px -8px 7px; padding:3px 6px; height:22px; color:#003c74; background:linear-gradient(#f7fbff,#c4daf3); border-bottom:1px solid #7f9db9; text-shadow:none; }
    .section-icon { width:15px; height:15px; border:0; background:transparent; }
    .field-grid { grid-template-columns:repeat(4,minmax(150px,1fr)); gap:5px 8px; }
    .field,.field.full { width:auto; min-width:0; }
    .field.full { grid-column:span 2; }
    .field.full input,.field.full select,.field.full textarea { width:100%; }
    label,legend { font-size:10px; }
    input,select,textarea { width:100%; height:23px; min-height:23px; padding:2px 5px; font-size:11px; }
    textarea { height:39px; min-height:39px; }
    .form-footer { position:static; padding:4px 7px; margin-top:0; }
    .taskbar { position:fixed; z-index:20; left:0; right:0; bottom:0; height:31px; display:flex; align-items:center; gap:5px; padding:2px 7px 2px 0; background:linear-gradient(#3e8cec,#0b56c4 48%,#0847a7 52%,#1c68d5); border-top:1px solid #8fc5ff; }
    .start-button { height:28px; padding:0 13px 0 7px; border:1px solid #1f2e3c; border-radius:0 3px 3px 0; color:#071a2c; background:linear-gradient(#fff,#d8e0e7 48%,#aab6c0 52%,#eef3f7); font:bold 11px Tahoma; text-shadow:1px 1px #fff; box-shadow:inset 1px 1px #fff; }
    .start-button:hover { background:linear-gradient(#fffde8,#f3d68e); }
    .task-item { min-width:165px; height:25px; padding:4px 10px; color:#fff; background:linear-gradient(#4895ed,#1962bf); border:1px solid #0b3b82; box-shadow:inset 1px 1px rgba(255,255,255,.35); }
    .task-item.active { background:linear-gradient(#164d9d,#0b377a); }
    .task-item:hover { background:linear-gradient(#6aa9ef,#276fc5); border-color:#c5e3ff; }
    .task-item.active:hover { background:linear-gradient(#255fa9,#0d3e83); }
    .window-motion { transition:transform .38s cubic-bezier(.4,0,.55,1),opacity .38s ease; transform-origin:top left; pointer-events:none; }
    .window-minimizing { transform:translate(var(--task-x),var(--task-y)) scale(.16,.06); opacity:.12; }
    .tray { margin-left:auto; color:#fff; font-size:11px; padding:5px 10px; border-left:1px solid #2f8cf1; }
    .start-menu { position:fixed; z-index:19; left:0; bottom:31px; width:298px; height:330px; padding:5px; display:grid; grid-template-columns:143px 1fr; grid-template-rows:20px 1fr; background:#3f4346; border:1px solid #1d262e; box-shadow:3px 3px 10px rgba(0,0,0,.45); transform:scaleY(.04); transform-origin:left bottom; opacity:0; visibility:hidden; transition:transform .34s cubic-bezier(.3,0,.2,1),opacity .22s ease,visibility 0s linear .34s; }
    .start-menu.open { transform:scaleY(1); opacity:1; visibility:visible; transition:transform .34s cubic-bezier(.3,0,.2,1),opacity .22s ease; }
    .start-menu h3 { grid-column:1/-1; margin:0; padding:0 17px; color:#fff; background:#3f4346; font:bold 11px Tahoma; line-height:20px; }
    .start-left { display:flex; flex-direction:column; padding:5px 0; background:#fff; border:1px solid #232c34; }
    .start-right { display:flex; flex-direction:column; padding:5px 0; color:#fff; background:#3f4346; }
    .start-user { padding:2px 7px 7px; color:#a8d6ff; font:bold 10px Tahoma; }
    .start-menu a { position:relative; display:block; padding:6px 9px; color:#111; text-decoration:none; font:10px Tahoma; }
    .start-left a.has-submenu:after { content:"▶"; position:absolute; right:9px; color:#1872c7; font-size:9px; }
    .start-right a { color:#fff; font-weight:bold; padding-left:27px; }
    .start-menu a:hover { background:#316ac5; color:#fff; }
    /* Left navigation adapted from the application menu. */
    .start-menu { width:212px; height:calc(100vh - 31px); padding:0; display:block; overflow:auto; background:#3d86e8; border:0; border-right:1px solid #fff; box-shadow:2px 0 8px rgba(0,0,0,.18); transform:translateX(-100%); transform-origin:left center; }
    .start-menu.open { transform:translateX(0); }
    .start-menu h3 { display:none; }
    .menu-brand { display:flex; align-items:center; gap:10px; height:66px; margin:0 8px 12px; padding:0 12px; color:#fff; background:linear-gradient(135deg,#1a65cf,#0e4fae); border:1px solid rgba(255,255,255,.38); border-radius:7px; box-shadow:inset 0 1px rgba(255,255,255,.18),0 2px 5px rgba(0,40,110,.25); font:700 16px/1 "Segoe UI",Tahoma,sans-serif; text-shadow:1px 1px rgba(0,34,100,.6); }
    .menu-brand:before { content:""; display:block; flex:0 0 40px; width:40px; height:32px; background:url('{{ asset('Sofitelspa-transparent.png') }}') center/contain no-repeat; }
    .menu-section { display:block; margin:0 17px 8px; color:#e7f1ff; font:700 11px/1 "Segoe UI",Tahoma,sans-serif; letter-spacing:.35px; text-shadow:1px 1px rgba(0,47,120,.6); }
    .menu-logo { display:block; width:174px; height:112px; margin:13px auto 18px; background:url('{{ asset('Sofitelspa-transparent.png') }}') center/contain no-repeat; }
    .start-left,.start-right { display:block; padding:14px 0 6px; color:#fff; background:transparent; border:0; }
    .start-right { padding-top:0; }
    .start-user { display:none; }
    .start-menu a,.start-right a { display:flex; align-items:center; min-height:38px; margin:2px 8px; padding:7px 10px; color:#fff; font:14px/1.2 "Segoe UI",Tahoma,sans-serif; font-weight:400; text-shadow:1px 1px rgba(0,51,125,.62); transition:background .16s ease,color .16s ease,transform .16s ease,box-shadow .16s ease; }
    .start-menu a:before { content:"○"; width:20px; margin-right:14px; color:#e7f1ff; font:17px/1 Arial,sans-serif; text-align:center; }
    .start-left a:nth-child(1):before { content:"⌂"; }.start-left a:nth-child(2):before { content:"▯"; }.start-left a:nth-child(3):before { content:"▣"; }.start-left a:nth-child(4):before { content:"☷"; }
    .start-left a:nth-child(5):before { content:"◌"; }.start-left a:nth-child(6):before { content:"◉"; }.start-left a:nth-child(7):before { content:"▰"; }.start-left a:nth-child(8):before { content:"☑"; }.start-left a:nth-child(9):before { content:"▤"; }.start-left a:nth-child(10):before { content:"◇"; }.start-left a:nth-child(11):before { content:"⚒"; }.start-left a:nth-child(12):before { content:"▣"; }.start-left a:nth-child(13):before { content:"▧"; }.start-left a:nth-child(14):before { content:"▥"; }.start-left a:nth-child(15):before { content:"⌁"; }
    .start-right a:nth-child(1):before { content:"◌"; }.start-right a:nth-child(2):before { content:"⚙"; }.start-right a:nth-child(3):before { content:"⌘"; }.start-right a:nth-child(4):before { content:"↪"; }
    .start-left a.has-submenu:after { display:none; }
    .start-menu a:hover,.start-menu a.active { color:#0a4da5; background:linear-gradient(135deg,#f3f8ff,#dbeaff); border-radius:6px; text-shadow:none; font-weight:600; box-shadow:0 1px 2px rgba(0,52,124,.14); transform:translateX(2px); }
    .start-menu a:hover:before,.start-menu a.active:before { color:#0a4da5; }
    .start-left a.submenu-toggle:after { content:"›"; margin-left:auto; color:inherit; font:700 21px/1 Arial; transition:transform .2s ease; }
    .start-left a.submenu-toggle.expanded:after { transform:rotate(90deg); }
    .submenu { max-height:0; margin:0 8px; overflow:hidden; opacity:0; transition:max-height .25s ease,opacity .18s ease; }
    .submenu.open { max-height:260px; opacity:1; }
    .submenu a { min-height:31px; margin:1px 0; padding:5px 9px 5px 40px; font-size:12px; }
    .submenu a:before { content:"○"; width:13px; margin-right:8px; font-size:12px; }
    body:after { content:""; position:fixed; z-index:1; inset:0 0 31px 212px; pointer-events:none; background:url('{{ asset('Sofitelspa-transparent.png') }}') center/min(58vw,760px) auto no-repeat; opacity:.12; }
    .wallpaper-picker { position:fixed; left:212px; bottom:31px; width:330px; padding:8px; display:none; grid-template-columns:repeat(3,1fr); gap:7px; background:#3f4346; border:1px solid #1d262e; box-shadow:3px 3px 10px rgba(0,0,0,.45); }
    .wallpaper-picker.open { display:grid; }
    .picker-label { grid-column:1/-1; padding:1px 2px; color:#fff; font:bold 10px Tahoma; }
    .wallpaper-picker button { height:72px; padding:0; overflow:hidden; border:2px solid #dbe6ef; border-radius:2px; background-size:cover; background-position:center; box-shadow:none; }
    .wallpaper-picker button:hover,.wallpaper-picker button.active { border-color:#ffd45f; outline:1px solid #fff; }
    .wallpaper-picker span { align-self:end; padding:3px; color:#fff; background:rgba(0,0,0,.65); font:9px Tahoma; }
    .wallpaper-picker .theme-swatch { height:42px; background-image:none; }
    .theme-swatch[data-theme="gray"] { background:linear-gradient(#f2f2f2,#8f969d); }
    .theme-swatch[data-theme="green"] { background:linear-gradient(#65d582,#078634); }
    .theme-swatch[data-theme="blue"] { background:linear-gradient(#72b8fa,#176ac2); }
    .aux-window { position:fixed; z-index:8; width:640px; height:430px; min-width:360px; min-height:240px; overflow:hidden; background:#e5f1e9; border:1px solid #075c2a; box-shadow:3px 5px 12px rgba(0,0,0,.38); }
    .aux-window .aux-titlebar { display:flex; align-items:center; height:27px; padding:3px 4px 3px 8px; color:#fff; cursor:move; user-select:none; background:linear-gradient(#54ce76,#078634 72%,#2db65a); font:bold 11px Tahoma; }
    .aux-titlebar .window-controls { margin-left:auto; }
    .aux-content { height:calc(100% - 27px); padding:7px; overflow:auto; background:#edf7f0; }
    .aux-toolbar { display:flex; gap:3px; align-items:center; height:27px; padding:2px 4px; margin:-7px -7px 7px; background:linear-gradient(#f8fbff,#c9dcf4); border-bottom:1px solid #7f9db9; }
    .aux-toolbar button { padding:2px 6px; height:21px; font-size:10px; background:transparent; border:1px solid transparent; box-shadow:none; }
    .aux-toolbar button:hover { background:#fff2bd; border-color:#dfa640; }
    .patient-list-shell { height:calc(100% - 34px); display:flex; flex-direction:column; padding:5px; background:#eaf5ed; border:1px solid #67a97a; box-shadow:inset 0 0 0 2px #f7fcf8; }
    .aux-window[data-window-id="listeler"] .aux-toolbar { background:linear-gradient(#fbfffc,#cde9d5); border-bottom-color:#78aa86; }
    .aux-window[data-window-id="listeler"] .aux-toolbar button:hover { background:#e2f5e7; border-color:#63a978; }
    .patient-list-caption { height:22px; display:flex; align-items:center; padding:0 7px; color:#075c2a; background:linear-gradient(#fbfffc,#cde9d5); border:1px solid #79b68a; font:bold 11px Tahoma; }
    .patient-list-filters { display:grid; grid-template-columns:auto minmax(220px,1fr) auto 145px; gap:4px; align-items:center; height:31px; padding:3px 6px; margin:4px 0; background:linear-gradient(#fbfffc,#d8ecdd); border:1px solid #79ac87; }
    .patient-list-filters label { display:contents; color:#164d29; font:bold 10px Tahoma; }
    .patient-list-filters input,.patient-list-filters select { width:100%; height:21px; min-height:21px; padding:1px 4px; border:1px solid #7da28a; border-radius:0; background:#fff; font:10px Tahoma; }
    .patient-grid-wrap { flex:1; min-height:0; overflow:auto; background:#fff; border:1px solid #79a988; }
    .aux-grid { width:100%; border-collapse:collapse; table-layout:fixed; background:#fff; color:#000; font:10px Tahoma; }
    .aux-grid th,.aux-grid td { height:22px; padding:2px 6px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; text-align:left; border-right:1px solid #c7d4e2; border-bottom:1px solid #dce4ed; }
    .aux-grid th { height:23px; color:#183d25; background:linear-gradient(#fff,#d8eadc); border-bottom:1px solid #82a98d; font-weight:bold; }
    .patient-table th { position:relative; padding-right:22px; overflow:visible; }
    .column-menu-button { position:absolute; top:1px; right:4px; width:18px; height:19px; padding:0; color:#244f83; background:linear-gradient(#fff,#d7e2f0); border:1px solid #8ba2bd; border-radius:0; box-shadow:inset 1px 1px #fff; font:bold 9px Tahoma; }
    .column-resizer { position:absolute; z-index:3; top:0; right:-3px; width:7px; height:100%; cursor:col-resize; touch-action:none; }
    .column-resizer:hover,.column-resizer.dragging { background:rgba(22,129,58,.3); }
    body.column-resizing,body.column-resizing * { cursor:col-resize !important; user-select:none !important; }
    .column-menu-button:hover,.column-menu-button.open { background:linear-gradient(#fff8d7,#f2c66d); border-color:#b9892e; }
    .column-context-menu { position:fixed; z-index:30000; width:155px; padding:2px; color:#111; background:#f2f2f2; border:1px solid #71869c; box-shadow:2px 3px 7px rgba(0,0,0,.35); font:10px Tahoma; }
    .column-context-menu button { width:100%; height:25px; display:flex; align-items:center; gap:7px; padding:3px 7px; text-align:left; color:#111; background:transparent; border:0; border-radius:0; box-shadow:none; font:10px Tahoma; }
    .column-context-menu button:hover { color:#fff; background:#316ac5; }
    .column-context-menu .menu-separator { height:1px; margin:2px 3px; background:#b7b7b7; border-bottom:1px solid #fff; }
    .column-context-menu .columns-trigger { justify-content:space-between; }
    .column-context-menu .columns-panel { position:absolute; top:52px; left:151px; width:150px; max-height:280px; overflow:auto; padding:3px; background:#f2f2f2; border:1px solid #71869c; box-shadow:2px 3px 7px rgba(0,0,0,.35); }
    .column-context-menu .columns-panel label { height:23px; display:flex; align-items:center; gap:6px; padding:2px 5px; color:#111; white-space:nowrap; font:10px Tahoma; }
    .column-context-menu .columns-panel label:hover { color:#fff; background:#316ac5; }
    .column-context-menu .columns-panel input { width:13px; height:13px; min-height:0; margin:0; accent-color:#316ac5; }
    .aux-grid tbody tr { cursor:default; } .aux-grid tbody tr:hover { background:#eff8f1; } .aux-grid tbody tr.selected { color:#075c2a; background:#c9e8d1; outline:1px dotted #43845a; outline-offset:-1px; }
    .setup-tabs { display:flex; gap:2px; padding:5px 6px 0; border-bottom:1px solid #b69445; background:#efe2bf; }
    .setup-tab { height:25px; padding:3px 13px; color:#5e4310; border:1px solid #b69445; border-bottom:0; border-radius:4px 4px 0 0; background:linear-gradient(#fffdf8,#ddc584); font:bold 10px Tahoma; box-shadow:none; }
    .setup-tab.active { position:relative; top:1px; background:#fff9ed; }
    .shift-panel { height:calc(100% - 33px); padding:8px; background:#fff9ed; }
    .shift-panel .aux-toolbar { margin:0 0 7px; border:1px solid #c9ad67; }
    .setup-panel[hidden] { display:none; }
    .business-hours-panel { height:calc(100% - 33px); padding:8px; background:#fff9ed; }
    .business-hours-panel .aux-toolbar { margin:0 0 7px; border:1px solid #c9ad67; }
    .business-hours-grid-wrap { overflow:auto; border:1px solid #c9ad67; background:#fff; }
    .business-hours-table { min-width:560px; }
    .business-hours-table th:first-child,.business-hours-table td:first-child { width:150px; }
    .business-hours-table input[type="time"] { width:130px; height:25px; padding:2px 5px; }
    .business-hours-table input[type="checkbox"] { width:15px; height:15px; min-height:0; vertical-align:middle; }
    .business-hours-table tr.is-closed { color:#8b6d35; background:#f1ead8; }
    .business-hours-table tr.is-closed input[type="time"] { opacity:.45; }
    .shift-grid-wrap { height:calc(100% - 35px); overflow:auto; border:1px solid #c9ad67; background:#fff; }
    .shift-table { min-width:560px; }
    .shift-table th:nth-child(1),.shift-table td:nth-child(1) { width:80px; }
    .shift-table th:nth-child(2),.shift-table td:nth-child(2) { width:250px; }
    .shift-table th:nth-child(3),.shift-table td:nth-child(3) { width:210px; }
    .shift-table td:first-child,.shift-table td:nth-child(2) { color:#397fce; font-size:12px; }
    .shift-table input { width:100%; height:22px; padding:1px 5px; border:1px solid #b69445; border-radius:0; background:#fffef9; font:11px Tahoma; }
    .shift-actions { display:flex; gap:4px; }
    .shift-actions button { height:21px; padding:1px 7px; font-size:10px; box-shadow:none; }
    .shift-edit { color:#175da8; } .shift-delete { color:#a3261d; } .shift-save { color:#146b2f; font-weight:bold; }
    .shift-message { margin-left:auto; color:#604510; font-size:10px; }
    .schedule-shell { height:calc(100% - 27px); display:flex; flex-direction:column; padding:7px; background:#fff9ed; }
    .schedule-toolbar { display:flex; align-items:center; gap:5px; padding:5px; border:1px solid #c9ad67; background:linear-gradient(#fffdf7,#ead7a4); }
    .schedule-toolbar button { height:25px; padding:2px 9px; font-size:10px; }
    .schedule-toolbar label { display:flex; align-items:center; gap:4px; font:bold 10px Tahoma; }
    .schedule-toolbar select { height:25px; min-width:125px; border:1px solid #b99b55; background:#fff; font:10px Tahoma; }
    .schedule-week-label { min-width:190px; text-align:center; color:#604510; font:bold 11px Tahoma; }
    .schedule-grid-wrap { flex:1; min-height:0; overflow:auto; margin-top:7px; border:1px solid #8c681a; background:#fff; }
    .schedule-table { width:100%; min-width:1040px; border-collapse:collapse; table-layout:fixed; color:#18130b; font:11px Tahoma; }
    .schedule-table th,.schedule-table td { height:43px; padding:4px; border:1px solid #c8a75a; text-align:center; }
    .schedule-table thead th { position:sticky; top:0; z-index:2; height:48px; color:#4c3504; background:#fff09a; }
    .schedule-table thead th:first-child { left:0; z-index:3; width:190px; background:#efb17b; }
    .schedule-table tbody th { position:sticky; left:0; z-index:1; padding-left:9px; text-align:left; color:#173b57; background:#d9e9f5; }
    .schedule-table .schedule-group-row th { position:static; height:26px; padding:4px 9px; color:#fff; background:#185344; text-align:left; letter-spacing:.3px; }
    .schedule-day-name { display:block; font:bold 11px Tahoma; text-transform:uppercase; }
    .schedule-day-date { display:block; margin-top:4px; font:10px Tahoma; }
    .schedule-cell { padding:3px !important; background:#fffdf7; }
    .schedule-cell select { width:100%; height:29px; min-height:29px; padding:2px 4px; border:1px solid #cbb36d; border-radius:0; background:#fff; font:bold 10px Tahoma; }
    .schedule-cell.state-off { background:#ffc61a; } .schedule-cell.state-off select { background:#ffc61a; }
    .schedule-cell.state-izin { background:#92d050; } .schedule-cell.state-izin select { background:#92d050; }
    .schedule-cell.state-raporlu { background:#91c75b; } .schedule-cell.state-raporlu select { background:#91c75b; }
    .schedule-empty { padding:35px !important; color:#775f2a; }
    .schedule-print-header { display:none; }
    @media print {
      @page { size:A4 landscape; margin:9mm; }
      body { background:#fff !important; }
      body > * { display:none !important; }
      body > .aux-window[data-window-id="shifts"] { position:static !important; inset:auto !important; display:block !important; width:auto !important; height:auto !important; min-width:0 !important; transform:none !important; border:0 !important; box-shadow:none !important; }
      .aux-window[data-window-id="shifts"] .aux-titlebar,
      .aux-window[data-window-id="shifts"] .schedule-toolbar { display:none !important; }
      .aux-window[data-window-id="shifts"] .schedule-shell { height:auto !important; padding:0 !important; background:#fff !important; }
      .aux-window[data-window-id="shifts"] .schedule-print-header { display:flex !important; align-items:center; justify-content:space-between; gap:12mm; margin-bottom:5mm; padding-bottom:3mm; border-bottom:1px solid #9b7a2d; }
      .schedule-print-logo { width:42mm; height:18mm; object-fit:contain; object-position:left center; }
      .schedule-print-title { flex:1; text-align:center; }
      .schedule-print-title h1 { margin:0; font:bold 16pt Tahoma; color:#173b2d; }
      .schedule-print-week-label { display:block; margin-top:2mm; font:bold 10pt Tahoma; color:#604510; }
      .aux-window[data-window-id="shifts"] .schedule-grid-wrap { overflow:visible !important; margin:0 !important; border:1px solid #8c681a !important; }
      .aux-window[data-window-id="shifts"] .schedule-table { width:100% !important; min-width:0 !important; font-size:7.5pt !important; }
      .aux-window[data-window-id="shifts"] .schedule-table th,
      .aux-window[data-window-id="shifts"] .schedule-table td { position:static !important; height:9mm !important; padding:1.2mm !important; }
      .aux-window[data-window-id="shifts"] .schedule-table thead th:first-child { width:38mm !important; }
      .aux-window[data-window-id="shifts"] .schedule-cell select { appearance:none; width:100%; height:auto; min-height:0; padding:0; border:0; background:transparent !important; color:#000; font:bold 7pt Tahoma; text-align:center; }
    }
    .employee-list-shell { height:calc(100% - 34px); display:flex; flex-direction:column; padding:5px; border:1px solid #c9ad67; background:#fbf3df; }
    .employee-grid-wrap { flex:1; min-height:0; overflow:auto; border:1px solid #c9ad67; background:#fff; }
    .employee-card-content { height:calc(100% - 27px); overflow:auto; padding:10px; background:#fff9ed; }
    .employee-card-form { min-width:720px; }
    .employee-card-toolbar { display:flex; align-items:center; gap:5px; padding:5px; margin-bottom:8px; border:1px solid #c9ad67; background:linear-gradient(#fffdf7,#ead7a4); }
    .employee-card-toolbar button { height:25px; padding:2px 9px; font-size:10px; }
    .employee-card-status { margin-left:auto; color:#604510; font-size:10px; }
    .employee-main { display:grid; grid-template-columns:190px minmax(430px,1fr); gap:14px; }
    .employee-photo-panel { border-right:1px solid #d4c08a; padding-right:12px; }
    .employee-photo { display:flex; align-items:center; justify-content:center; width:184px; height:210px; overflow:hidden; border:1px solid #c7c7c7; background:linear-gradient(#eee,#d7d7d7); color:#929292; font-size:70px; }
    .employee-photo img { width:100%; height:100%; object-fit:cover; }
    .employee-photo-url { width:100%; margin-top:6px; }
    .employee-photo-upload { display:block; margin-top:6px; padding:6px; text-align:center; color:#285f99; border:1px solid #b8a36d; background:linear-gradient(#fff,#e8ddc0); cursor:pointer; font:bold 10px Tahoma; }
    .employee-photo-upload input { display:none; }
    .employee-side-tabs { display:grid; gap:2px; margin-top:13px; }
    .employee-side-tabs button { height:auto; min-height:29px; padding:6px 8px; text-align:left; color:#428bd1; border:1px solid transparent; background:transparent; box-shadow:none; }
    .employee-side-tabs button.active { color:#4f4a40; border-color:#d4c08a; background:#fffdf7; }
    .employee-fields { display:grid; grid-template-columns:140px minmax(240px,1fr); gap:8px 10px; align-content:start; }
    .employee-fields label { align-self:center; color:#4f4a40; font-size:11px; }
    .employee-fields input,.employee-fields select { width:100%; height:28px; padding:3px 7px; border:1px solid #cab678; border-radius:0; background:#fff; font:11px Tahoma; }
    .employee-id-row { display:grid; grid-template-columns:1fr auto auto; gap:7px; }
    .employee-id-row button { height:28px; padding:3px 10px; font-size:10px; }
    .employee-card-section-title { grid-column:1/-1; padding:7px 9px; margin-bottom:3px; color:#fff; background:#d25254; font:bold 12px Tahoma; }
    .employee-tab-panel[hidden] { display:none; }
    .employee-contact-fields textarea { width:100%; min-height:82px; padding:6px 7px; resize:vertical; border:1px solid #cab678; background:#fff; font:11px Tahoma; }
    .status-badge { color:inherit; background:none; border:0; padding:0; font:inherit; }
    .list-pager { display:flex; align-items:center; gap:3px; height:27px; padding:2px 5px; margin-top:4px; color:#173d24; background:linear-gradient(#f8fcf9,#cee7d5); border:1px solid #79a988; font:10px Tahoma; }
    .list-pager button { width:20px; height:20px; padding:0; color:#16813a; background:transparent; border:0; box-shadow:none; font:10px Tahoma; } .list-pager button:hover { background:#e2f5e7; border:1px solid #63a978; }
    .list-pager input { width:38px; height:20px; min-height:20px; padding:1px 3px; text-align:right; border:1px solid #9db3ca; border-radius:0; font:10px Tahoma; } .list-count { margin-left:auto; font-weight:bold; }
    .member-card-content { height:calc(100% - 27px); padding:7px; overflow:auto; color:#231f16; background:#f6edd8; }
    .member-card-form { min-width:690px; }
    .member-card-toolbar { display:flex; gap:3px; height:28px; padding:3px 4px; margin:-7px -7px 7px; background:linear-gradient(#fffdf7,#ead7a4); border-bottom:1px solid #c9ad67; }
    .member-card-toolbar button { height:21px; padding:2px 9px; color:#604510; background:transparent; border:1px solid transparent; box-shadow:none; font:10px Tahoma; }
    .member-card-toolbar button:hover { background:#f5df99; border-color:#ad7e1f; }
    .member-card-section { margin:0 0 7px; padding:7px 9px 8px; border:1px solid #c9ad67; background:#fffaf0; box-shadow:inset 1px 1px #fff; }
    .member-card-section legend { padding:0 6px; color:#604510; font:bold 10px Tahoma; }
    .member-card-grid { display:grid; grid-template-columns:190px minmax(180px,1fr) 190px minmax(180px,1fr); gap:5px 8px; align-items:center; }
    .member-card-grid label { margin:0; color:#3c3323; font:bold 10px Tahoma; }
    .member-card-grid input,.member-card-grid select,.member-card-grid textarea { width:100%; height:23px; min-height:23px; padding:2px 5px; color:#211d15; background:#fffdf8; border:1px solid #b9a56c; border-radius:0; box-shadow:inset 1px 1px 2px rgba(0,0,0,.09); font:10px Tahoma; }
    .member-card-grid textarea { height:42px; resize:vertical; }
    .member-card-grid .wide-label { align-self:start; padding-top:4px; }
    .member-card-grid .wide-field { grid-column:span 3; }
    .member-card-status { min-height:18px; margin-left:auto; padding:3px 8px; color:#245849; font:bold 10px Tahoma; }
    .member-card-hint { margin:1px 0 7px; color:#76694e; font:9px Tahoma; }
    .aux-window[data-window-id^="member-"] { width:900px; height:530px; min-width:720px; min-height:420px; border-color:#8f6918; background:#f4ead2; }
    @media (max-width:760px) { .member-card-form { min-width:0; } .member-card-grid { grid-template-columns:145px minmax(190px,1fr); } .member-card-grid .wide-field { grid-column:auto; } .aux-window[data-window-id^="member-"] { min-width:360px; width:calc(100vw - 16px) !important; } }
    .aux-window.maximized { top:0 !important; left:0 !important; width:100vw !important; height:calc(100vh - 31px) !important; }
    .desktop-icon.selected { background:rgba(49,106,197,.5); outline:1px dotted #fff; }
    .classic-tool-button { display:inline-grid; place-items:center; width:20px; height:20px; min-width:20px; padding:0; color:#245d9c; background:linear-gradient(#fff,#dce8f6); border:1px solid #8ba8c7; border-radius:1px; box-shadow:inset 1px 1px #fff; font:bold 12px Tahoma; }
    .classic-tool-button:hover { background:linear-gradient(#fffde9,#ffd985); border-color:#d29a32; }
    .lookup-field { display:flex; width:100%; } .lookup-field input { flex:1; min-width:0; border-right:0; } .lookup-field .classic-tool-button { height:23px; }
    .select-shell { position:relative; display:block; width:100%; }
    .select-shell select { appearance:none; padding-right:23px; }
    .select-arrow { position:absolute; pointer-events:none; top:1px; right:1px; bottom:1px; width:19px; display:grid; place-items:center; color:#244f83; background:linear-gradient(#fff,#d7e2f0); border-left:1px solid #8ba2bd; font:bold 10px Tahoma; }
    .date-field { display:flex; width:100%; } .date-field input { flex:1; min-width:0; border-right:0; } .date-field .classic-tool-button { height:23px; }
    .compact-calendar { position:fixed; z-index:9999; width:178px; padding:4px; color:#17283d; background:#fff; border:1px solid #6e8ead; box-shadow:2px 3px 8px rgba(0,0,0,.3); font:10px Tahoma,sans-serif; }
    .calendar-head { display:grid; grid-template-columns:22px 1fr 22px; align-items:center; margin-bottom:3px; background:#dce9f8; border:1px solid #aac0d9; }
    .calendar-head button { width:20px; height:20px; padding:0; border:0; box-shadow:none; background:transparent; color:#245d9c; }
    .calendar-title { text-align:center; font-weight:bold; }
    .calendar-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:1px; }
    .calendar-weekday { height:16px; display:grid; place-items:center; font-weight:bold; color:#34516f; }
    .calendar-day { width:22px; height:20px; min-width:0; padding:0; border:1px solid transparent; border-radius:0; background:#fff; box-shadow:none; font:10px Tahoma; }
    .calendar-day:hover { background:#fff1b8; border-color:#d9a73c; } .calendar-day.today { border-color:#3977b8; } .calendar-day.selected { color:#fff; background:#176ac2; }
    .calendar-foot { display:flex; justify-content:space-between; padding-top:4px; margin-top:3px; border-top:1px solid #d7e0ea; }
    .calendar-foot button { padding:2px 4px; border:0; box-shadow:none; background:transparent; color:#075bb5; font-size:10px; }
    @media (max-width:900px) { .desktop-icons { display:none; } .app { top:8px; left:8px; right:8px; width:auto; height:calc(100vh - 47px); } .field-grid { grid-template-columns:repeat(2,minmax(140px,1fr)); } }
    /* Vox green window theme */
    .app { border-color:#075c2a; background:#dcefe3; }
    main { background:#e5f1e9; }
    .topbar { background:linear-gradient(180deg,#54ce76 0,#21a64e 18%,#078634 72%,#2db65a 100%); border-color:#075c2a; }
    .topbar .actions button { background:linear-gradient(#7be395,#169344); border-color:#d6ffe1; }
    .card { background:#edf7f0; border-color:#70a985; }
    .card-heading { color:#075c2a; background:linear-gradient(#fbfffc,#c6e7d1); border-bottom-color:#70a985; }
    .section-icon { color:#078634; }
    .form-footer { background:#dcebdd; border-color:#70a985; }
    .start-menu { border-color:#fff; }
    .start-menu h3 { background:linear-gradient(#45c86d,#078634); }
    body.theme-gray .app,body.theme-gray .aux-window { border-color:#505860; background:#e7e9eb; }
    body.theme-gray main,body.theme-gray .aux-content { background:#eef0f2; }
    body.theme-gray .topbar,body.theme-gray .aux-titlebar { background:linear-gradient(#c8cdd1,#7b838a 65%,#a7adb2); border-color:#505860; }
    body.theme-gray .card,body.theme-gray .patient-list-shell { background:#f1f2f3; border-color:#9ba1a6; }
    body.theme-gray .card-heading,body.theme-gray .patient-list-caption,body.theme-gray .patient-list-filters { color:#30353a; background:linear-gradient(#fff,#d9dcdf); border-color:#a0a5aa; }
    body.theme-gray .section-icon { color:#626a70; }
    body.theme-gray .form-footer,body.theme-gray .list-pager { background:#e1e3e5; border-color:#9ba1a6; }
    body.theme-blue .app,body.theme-blue .aux-window { border-color:#174f8a; background:#dce9f7; }
    body.theme-blue main,body.theme-blue .aux-content { background:#eaf2fb; }
    body.theme-blue .topbar,body.theme-blue .aux-titlebar { background:linear-gradient(#72b8fa,#176ac2 70%,#438fd8); border-color:#174f8a; }
    body.theme-blue .card,body.theme-blue .patient-list-shell { background:#f0f6fc; border-color:#7fa8cf; }
    body.theme-blue .card-heading,body.theme-blue .patient-list-caption,body.theme-blue .patient-list-filters { color:#174f8a; background:linear-gradient(#fff,#d2e4f6); border-color:#86abd0; }
    body.theme-blue .section-icon { color:#176ac2; }
    body.theme-blue .form-footer,body.theme-blue .list-pager { background:#dbe9f7; border-color:#7fa8cf; }
    .window-controls { display:flex; gap:2px; margin-left:auto; }
    .window-controls button { width:22px; height:20px; padding:0; color:#fff; border:1px solid #d8ffe2; border-radius:2px; background:linear-gradient(#69da88,#0b8437); font:bold 12px Tahoma; line-height:17px; }
    .window-controls .window-close { background:linear-gradient(#ef8c75,#c8321d); border-color:#ffe1d8; }
    .app.maximized { top:0 !important; left:0 !important; right:auto !important; width:100vw !important; height:calc(100vh - 31px) !important; border-radius:0; }
    .app.maximized main { transform-origin:top left; max-height:none; }
    .app.minimized { display:none; }
    #window-titlebar { cursor:move; user-select:none; touch-action:none; }
    #window-titlebar button { cursor:pointer; }
    .window-resizer { display:none; }
    .app.maximized .window-resizer { display:none; }
    .app { touch-action:none; container-type:inline-size; max-width:100vw; max-height:calc(100vh - 31px); }
    .app:not(.maximized) { height:auto; }
    .app:not(.maximized) main { height:auto; max-height:calc(100vh - 31px); }
    .app.frame-resizing,.app.frame-resizing * { user-select:none !important; }
    @container (max-width:850px) { .field-grid { grid-template-columns:repeat(2,minmax(150px,1fr)); } }
    @container (max-width:620px) { .field-grid { grid-template-columns:1fr; } .field.full { grid-column:auto; } }
    /* Sofitel Spa emerald and gold application theme */
    .app,.aux-window { border-color:#8f6918; background:#f4ead2; }
    main,.aux-content { background:#fff9ed; }
    .topbar,.aux-window .aux-titlebar { background:linear-gradient(180deg,#3d7061 0,#245849 20%,#123c32 72%,#1d4d40 100%); border-color:#8f6918; }
    .topbar .actions button,.window-controls button { color:#fff; background:linear-gradient(#e6c665,#ad7e1f); border-color:#f4dda0; text-shadow:1px 1px #6a490d; }
    .card,.patient-list-shell { background:#fffaf0; border-color:#c9ad67; }
    .card-heading,.patient-list-caption,.patient-list-filters { color:#604510; background:linear-gradient(#fffdf7,#ead7a4); border-color:#c9ad67; }
    .section-icon { color:#ad7e1f; }
    .form-footer,.list-pager { background:#f3e8cc; border-color:#c9ad67; }
    .taskbar { background:linear-gradient(#245849,#123c32 55%,#09261f); border-top-color:#d8b95d; }
    .start-button { color:#fff; background:linear-gradient(#e6c665,#ad7e1f 55%,#8f6918); border-color:#6a490d; text-shadow:1px 1px #6a490d; }
    .start-button:hover { background:linear-gradient(#f1d77d,#c4932d); }
    .task-item { color:#fff; background:linear-gradient(#3d7061,#1d4d40); border-color:#8f6918; }
    .task-item.active,.task-item.active:hover { background:linear-gradient(#cda341,#8f6918); }
    .task-item:hover { background:linear-gradient(#527f72,#245849); border-color:#e3c66e; }
    .tray { border-left-color:#8f6918; }
    .start-menu { background:linear-gradient(180deg,#245849,#123c32 58%,#09261f); border-right-color:#d8b95d; }
    .menu-brand { color:#fff9ed; background:linear-gradient(135deg,#365f53,#123c32); border-color:rgba(227,198,110,.65); box-shadow:inset 0 1px rgba(255,255,255,.14),0 2px 5px rgba(0,0,0,.24); text-shadow:1px 1px #09261f; }
    .menu-section { color:#e3c66e; text-shadow:1px 1px #09261f; }
    .start-menu a,.start-right a { color:#fff9ed; text-shadow:1px 1px #09261f; }
    .start-menu a:before { color:#e3c66e; }
    .start-menu a:hover,.start-menu a.active { color:#604510; background:linear-gradient(135deg,#fff9ed,#ead7a4); box-shadow:0 1px 2px rgba(45,32,7,.2); }
    .start-menu a:hover:before,.start-menu a.active:before { color:#ad7e1f; }
    .desktop-icon { text-shadow:1px 1px 2px #09261f; }
    input:focus,select:focus,textarea:focus { border-color:#b18424; box-shadow:0 0 0 1px #e3c66e,inset 1px 1px 2px rgba(0,0,0,.1); }
    .primary { color:#fff; background:linear-gradient(#e6c665,#ad7e1f); border-color:#7a5713; text-shadow:1px 1px #6a490d; }
    .primary:hover { background:linear-gradient(#f1d77d,#c4932d); }
    .aux-content { background:#fff9ed; }
    .aux-toolbar,.aux-window[data-window-id="listeler"] .aux-toolbar { background:linear-gradient(#fffdf7,#ead7a4); border-bottom-color:#c9ad67; }
    .aux-toolbar button { color:#604510; }
    .aux-toolbar button:hover,.aux-window[data-window-id="listeler"] .aux-toolbar button:hover { color:#2b261c; background:#f5df99; border-color:#ad7e1f; }
    .patient-list-shell { background:#fbf3df; border-color:#c9ad67; box-shadow:inset 0 0 0 2px #fffdf8; }
    .patient-list-caption { color:#604510; background:linear-gradient(#fffdf7,#ead7a4); border-color:#c9ad67; }
    .patient-list-filters { color:#604510; background:linear-gradient(#fffdf7,#f1e5c6); border-color:#c9ad67; }
    .patient-list-filters label { color:#604510; }
    .patient-list-filters input,.patient-list-filters select { color:#2b261c; background:#fffdf8; border-color:#c9ad67; }
    .patient-grid-wrap { background:#fffdf8; border-color:#c9ad67; }
    .aux-grid { color:#2b261c; background:#fffdf8; }
    .aux-grid th,.aux-grid td { border-right-color:#e4d7b7; border-bottom-color:#eee4ca; }
    .aux-grid th { color:#604510; background:linear-gradient(#fffdf8,#ead7a4); border-bottom-color:#c9ad67; }
    .aux-grid tbody tr:nth-child(even) { background:#fbf3df; }
    .aux-grid tbody tr:hover { background:#f6e6b8; }
    .aux-grid tbody tr.selected { color:#2b261c; background:#ead08a; outline-color:#8f6918; }
    .column-menu-button,.select-arrow,.classic-tool-button { color:#604510; background:linear-gradient(#fffdf8,#ead7a4); border-color:#c9ad67; }
    .column-menu-button:hover,.column-menu-button.open,.classic-tool-button:hover { color:#2b261c; background:linear-gradient(#fff5d3,#e3c66e); border-color:#ad7e1f; }
    .column-resizer:hover,.column-resizer.dragging { background:rgba(177,132,36,.28); }
    .column-context-menu,.column-context-menu .columns-panel { color:#2b261c; background:#fff9ed; border-color:#8f6918; }
    .column-context-menu button,.column-context-menu .columns-panel label { color:#2b261c; }
    .column-context-menu button:hover,.column-context-menu .columns-panel label:hover { color:#fff; background:#8f6918; }
    .column-context-menu .columns-panel input { accent-color:#ad7e1f; }
    .list-pager { color:#604510; background:linear-gradient(#fffdf7,#ead7a4); border-color:#c9ad67; }
    .list-pager button { color:#8f6918; }
    .list-pager button:hover { color:#604510; background:#f5df99; border-color:#ad7e1f; }
    .list-pager input { color:#2b261c; background:#fffdf8; border-color:#c9ad67; }
    .compact-calendar { color:#2b261c; background:#fff9ed; border-color:#8f6918; }
    .calendar-head { color:#604510; background:#ead7a4; border-color:#c9ad67; }
    .calendar-head button,.calendar-foot button { color:#8f6918; }
    .calendar-weekday { color:#604510; }
    .calendar-day { color:#2b261c; background:#fffdf8; }
    .calendar-day:hover { background:#f5df99; border-color:#ad7e1f; }
    .calendar-day.today { border-color:#8f6918; }
    .calendar-day.selected { color:#fff; background:#ad7e1f; }
    .calendar-foot { border-top-color:#c9ad67; }
    .wallpaper-picker { background:#123c32; border-color:#8f6918; }
    .wallpaper-picker button:hover,.wallpaper-picker button.active { border-color:#e3c66e; }
  </style>
</head>
<body>
  <div class="login-screen" id="login-screen"><form class="login-panel" id="login-form" method="post" action="{{ route('login') }}" novalidate>@csrf<img class="login-logo" src="{{ asset('Sofitelspa-transparent.png') }}" alt="Sofitel Spa"><h1 class="login-title">Kullanıcı Girişi</h1><p class="login-subtitle">Şimdilik bilgileri doldurmadan devam edebilirsiniz.</p><div class="login-field"><label for="login-user">E-posta <small>(opsiyonel)</small></label><input id="login-user" name="email" type="email" autocomplete="username" autofocus></div><div class="login-field"><label for="login-password">Şifre <small>(opsiyonel)</small></label><input id="login-password" name="password" type="password" autocomplete="current-password"></div><div class="login-options"><label><input type="checkbox" id="remember-user" name="remember" value="1">E-postayı hatırla</label><span>Geçici erişim</span></div><button class="login-submit" type="button" onclick="spaEnter(event)">Giriş Yap</button><p class="login-error" id="login-error" role="alert"></p></form></div>
  <div class="desktop-status"><img src="{{ asset('Sofitelspa-transparent.png') }}" alt="Sofitel Spa"></div>
  <div class="desktop-icons"><button class="desktop-icon" data-open="patient" data-title="Yeni Hasta Kaydı"><span class="ico"><svg viewBox="0 0 54 48" aria-hidden="true"><path fill="#f4b629" stroke="#8a5a00" d="M3 10h19l4 5h25v28H3z"/><path fill="#ffd86a" stroke="#9d6b08" d="M3 16h48l-5 27H7z"/><circle cx="39" cy="31" r="10" fill="#fff" stroke="#137c3c"/><path stroke="#149447" stroke-width="4" d="M39 25v12M33 31h12"/></svg></span>Hasta Kartları</button><button class="desktop-icon" data-open="personeller" data-title="Personeller"><span class="ico"><svg viewBox="0 0 54 48" aria-hidden="true"><rect x="5" y="4" width="44" height="40" rx="3" fill="#2f9d49" stroke="#0b5720"/><path fill="#91d778" d="M9 9h36v30H9z"/><circle cx="21" cy="20" r="6" fill="#fff"/><path fill="#fff" d="M11 35c1-8 5-11 10-11s9 3 10 11z"/><circle cx="36" cy="19" r="5" fill="#eaffdf"/><path fill="#eaffdf" d="M29 34c1-7 3-10 7-10s7 3 8 10z"/></svg></span>Personeller</button><button class="desktop-icon" data-open="listeler" data-title="Hasta Listeleri"><span class="ico"><svg viewBox="0 0 54 48" aria-hidden="true"><rect x="8" y="3" width="38" height="42" rx="2" fill="#f5f1de" stroke="#5d6670"/><rect x="18" y="1" width="18" height="7" rx="2" fill="#7e8b98"/><path stroke="#3378bd" stroke-width="2" d="M18 15h21M18 23h21M18 31h21M18 39h15"/><path fill="#3aa14a" d="M11 12h5v5h-5zM11 20h5v5h-5zM11 28h5v5h-5zM11 36h5v5h-5z"/></svg></span>Listeler</button><button class="desktop-icon" data-open="evraklar" data-title="Evraklar"><span class="ico"><svg viewBox="0 0 54 48" aria-hidden="true"><path fill="#eaf3ff" stroke="#45698f" d="M9 3h25l11 11v31H9z"/><path fill="#b9d9f7" d="M34 3v12h11z"/><path stroke="#4e86ba" stroke-width="2" d="M15 20h24M15 27h24M15 34h19"/><circle cx="42" cy="37" r="9" fill="#f0b21f" stroke="#805600"/><path fill="#fff" d="M40 31h4v8h-4zM40 41h4v3h-4z"/></svg></span>Evraklar</button></div>
  <div class="start-menu" id="start-menu"><h3>Menü</h3><div class="start-left"><a href="#">Üyeler</a><a href="#">Rezervasyon</a><a href="#">Ön Kasa</a><a href="#">Stok</a><a href="#">Personel</a><a href="#">Raporlar</a><a href="#">Kurulum</a></div><div class="start-right"><a href="#" id="wallpaper-menu-button">Görünüm Ayarları</a><a href="#" id="logout-button">Çıkış</a></div><div class="wallpaper-picker" id="wallpaper-picker"><div class="picker-label">Duvar Kâğıdı</div><button type="button" data-wallpaper="wallpaper-blue.png" style="background-image:url('wallpaper-blue.png')"><span>Mavi</span></button><button type="button" data-wallpaper="wallpaper-green.png" style="background-image:url('wallpaper-green.png')"><span>Yeşil</span></button><button type="button" data-wallpaper="wallpaper-purple.png" style="background-image:url('wallpaper-purple.png')"><span>Mor</span></button><div class="picker-label">Ekran Rengi</div><button type="button" class="theme-swatch" data-theme="gray"><span>Gri</span></button><button type="button" class="theme-swatch" data-theme="green"><span>Yeşil</span></button><button type="button" class="theme-swatch" data-theme="blue"><span>Mavi</span></button></div></div>
  <div class="app" hidden>
    <aside class="sidebar"><div class="brand"><span class="brand-mark">+</span> Klinik Paneli</div><nav class="nav"><div class="nav-label">YÖNETİM</div><a href="#">▦ Genel Bakış</a><a class="active" href="#">♙ Hastalar</a><a href="#">▣ Randevular</a><a href="#">▤ Raporlar</a></nav></aside>
    <main>
      <header class="topbar" id="window-titlebar"><div><h1>Yeni Hasta Kaydı</h1><p class="subtitle">Hasta bilgilerini girerek yeni bir kayıt oluşturun.</p></div><div class="window-controls"><button type="button" id="window-minimize" aria-label="Simge durumuna küçült">_</button><button type="button" id="window-maximize" aria-label="Tam ekran">□</button><button type="button" class="window-close" id="window-close" aria-label="Kapat">×</button></div></header>
      <form id="patient-form" method="post" action="hasta-kaydet.php">
        <section class="card primary-card"><h2 class="card-heading"><span class="section-icon">♙</span>Temel Bilgiler</h2><div class="field-grid">
          <div class="field"><label for="ad_soyad">Ad Soyad <span class="required">*</span></label><input id="ad_soyad" name="ad_soyad" autocomplete="name" required></div>
          <div class="field"><label for="tc_kimlik_no">T.C. Kimlik No</label><div class="lookup-field"><input id="tc_kimlik_no" name="tc_kimlik_no" inputmode="numeric" maxlength="11" placeholder="11 haneli T.C. Kimlik No"><button class="classic-tool-button" type="button" aria-label="Kimlik numarasıyla ara">⌕</button></div></div>
          <div class="field"><label for="sube">Şube <span class="required">*</span></label><select id="sube" name="sube" required><option value="">Şube seçin</option><option>Merkez Şube</option></select></div>
          <div class="field"><label for="kayit_tarihi">Kayıt Tarihi <span class="required">*</span></label><input id="kayit_tarihi" name="kayit_tarihi" type="text" inputmode="numeric" placeholder="gg.aa.yyyy" data-compact-date required></div>
          <div class="field"><label for="dogum_tarihi">Doğum Tarihi</label><input id="dogum_tarihi" name="dogum_tarihi" type="text" inputmode="numeric" placeholder="gg.aa.yyyy" data-compact-date></div>
          <div class="field"><label for="telefon1">Telefon 1</label><input id="telefon1" name="telefon1" type="tel" autocomplete="tel" placeholder="05xx xxx xx xx"></div>
          <div class="field"><label for="yakinlik_derecesi">Yakınlık Derecesi</label><input id="yakinlik_derecesi" name="yakinlik_derecesi" placeholder="Kendisi, eşi, yakını..."></div>
          <div class="field"><label for="telefon2">Telefon 2</label><input id="telefon2" name="telefon2" type="tel" placeholder="Telefon veya kişi bilgisi"></div>
          <div class="field full"><label for="adres">Adres</label><textarea id="adres" name="adres" placeholder="Adres bilgilerini giriniz"></textarea></div>
          <div class="field full"><label>Değerlendirme</label><div class="rating" role="radiogroup" aria-label="Değerlendirme"><button type="button" data-rate="1">☆</button><button type="button" data-rate="2">☆</button><button type="button" data-rate="3">☆</button><button type="button" data-rate="4">☆</button><button type="button" data-rate="5">☆</button><input type="hidden" name="degerlendirme" id="rating-value"></div></div>
          <div class="field full"><label for="yorum">Yorum</label><input id="yorum" name="yorum" placeholder="Yorum giriniz"></div>
          <fieldset class="field full"><legend>Hasta Durumu</legend><div class="radios"><label><input type="radio" name="hasta_durumu" value="aktif" checked>Aktif</label><label><input type="radio" name="hasta_durumu" value="vefat">Vefat</label></div></fieldset>
        </div></section>
        <section class="card"><h2 class="card-heading"><span class="section-icon">⌑</span>Hizmet Bilgileri</h2><div class="field-grid"><div class="field full"><label for="sosyal_guvence">Sosyal Güvence</label><select id="sosyal_guvence" name="sosyal_guvence"><option value="">Seçiniz</option><option>SGK</option><option>Özel Sigorta</option></select></div><div class="field full"><label for="rapor">Rapor</label><select id="rapor" name="rapor"><option value="">Seçiniz</option><option>Mevcut</option><option>Yok</option></select></div></div></section>
        <section class="card"><h2 class="card-heading"><span class="section-icon">☷</span>Başvuru ve Açıklamalar</h2><div class="field-grid"><div class="field full"><label for="kaynak">Kaynak</label><select id="kaynak" name="kaynak"><option value="">Seçiniz</option><option>Telefon</option><option>Web Sitesi</option><option>Yönlendirme</option></select></div><div class="field full"><label for="basvuru_detayi">Başvuru Detayı</label><input id="basvuru_detayi" name="basvuru_detayi" placeholder="Başvuru detayı giriniz"></div><div class="field full"><label for="aciklama">Açıklama</label><textarea id="aciklama" name="aciklama" placeholder="Açıklama giriniz"></textarea></div></div></section>
        <footer class="form-footer"><span class="hint">Zorunlu alanlar <span class="required">*</span> ile gösterilmiştir.</span><div class="footer-actions"><button class="primary" type="submit">💾 Kaydet (F2)</button></div></footer>
      </form>
    </main>
    <div class="window-resizer" id="window-resizer" aria-hidden="true"></div>
  </div>
  <div class="taskbar"><button type="button" class="start-button" id="start-button">◉ Başlat</button><button type="button" class="task-item" id="patient-task" hidden>Yeni Hasta Kaydı</button><span class="tray" id="desktop-clock">00:00</span></div>
  <script>
    const loginScreen=document.getElementById('login-screen'),loginForm=document.getElementById('login-form'),loginError=document.getElementById('login-error'),loginUser=document.getElementById('login-user');
    let csrfToken=document.querySelector('meta[name="csrf-token"]').content,accessToken=null;
    const endpoints={login:@json(route('login')),logout:@json(url('/logout')),members:@json(url('/api/members')),workShifts:@json(url('/api/work-shifts')),businessHours:@json(url('/api/business-hours')),occupations:@json(url('/api/occupations')),workGroups:@json(url('/api/work-groups')),employees:@json(url('/api/employees')),employeeSchedules:@json(url('/api/employee-schedules'))};
    const apiFetch=async(url,options={})=>{const isForm=options.body instanceof FormData,response=await fetch(url,{credentials:'same-origin',headers:{'Accept':'application/json',...(!isForm?{'Content-Type':'application/json'}:{}),'X-CSRF-TOKEN':csrfToken,...(accessToken?{'Authorization':'Bearer '+accessToken}:{}),...(options.headers||{})},...options});const payload=await response.json().catch(()=>({message:'Sunucu yanıtı okunamadı.'}));if(!response.ok)throw new Error(payload.message||Object.values(payload.errors||{}).flat()[0]||'İşlem tamamlanamadı.');return payload;};
    let memberRecords=[];
    const loadMembers=async()=>{const payload=await apiFetch(endpoints.members);memberRecords=payload.data;return memberRecords;};
    const rememberedUser=localStorage.getItem('spaRememberedEmail');if(rememberedUser){loginUser.value=rememberedUser;document.getElementById('remember-user').checked=true;}
    const authenticated=@json(auth()->check());if(authenticated){loginScreen.classList.add('hidden');loadMembers().catch(()=>loginScreen.classList.remove('hidden'));}
    window.spaEnter=async event=>{event.preventDefault();const email=loginUser.value.trim(),password=document.getElementById('login-password').value,remember=document.getElementById('remember-user').checked;loginError.textContent='Giriş yapılıyor…';try{const loginPayload=await apiFetch(endpoints.login,{method:'POST',body:JSON.stringify({email,password,remember})});accessToken=loginPayload.accessToken;csrfToken=loginPayload.csrfToken;document.querySelector('meta[name="csrf-token"]').content=csrfToken;if(remember&&email)localStorage.setItem('spaRememberedEmail',email);else localStorage.removeItem('spaRememberedEmail');await loadMembers();loginError.textContent='';loginScreen.classList.add('hidden');}catch(error){loginError.textContent=error.message;}return false;};
    loginForm.addEventListener('submit',window.spaEnter);
    const stars = document.querySelectorAll('[data-rate]');
    stars.forEach(star => star.addEventListener('click', () => { const value = Number(star.dataset.rate); document.getElementById('rating-value').value = value; stars.forEach(item => { const on = Number(item.dataset.rate) <= value; item.textContent = on ? '★' : '☆'; item.classList.toggle('selected', on); }); }));
    const startMenu=document.getElementById('start-menu'),startButton=document.getElementById('start-button');
    startMenu.insertAdjacentHTML('afterbegin','<span class="menu-logo" role="img" aria-label="Sofitel Spa"></span>');
    const submenuDefinitions={'Personel':['Çalışma Programı']};
    Object.entries(submenuDefinitions).forEach(([parentLabel,children])=>{
      const parent=[...startMenu.querySelectorAll('.start-left > a')].find(link=>link.textContent.trim()===parentLabel);if(!parent)return;
      const submenu=document.createElement('div');submenu.className='submenu';
      children.forEach(label=>{let link=[...startMenu.querySelectorAll('.start-left > a')].find(item=>item.textContent.trim()===label);if(!link){link=document.createElement('a');link.href='#';link.textContent=label;}submenu.appendChild(link);});
      parent.classList.add('submenu-toggle');parent.after(submenu);
      parent.addEventListener('click',event=>{event.preventDefault();event.stopPropagation();const open=!submenu.classList.contains('open');submenu.classList.toggle('open',open);parent.classList.toggle('expanded',open);});
    });
    startMenu.querySelector('.start-left a:first-child').classList.add('active');
    startButton.addEventListener('click', () => { if(!startMenu.classList.contains('open')) startMenu.classList.add('open'); });
    startButton.addEventListener('dblclick', event => { event.preventDefault(); startMenu.classList.remove('open'); });
    const wallpaperPicker=document.getElementById('wallpaper-picker'),wallpaperButton=document.getElementById('wallpaper-menu-button');
    const applyWallpaper=file=>{const matte=file==='matte-green';document.body.style.backgroundColor='#09261f';document.body.style.backgroundImage=matte?'radial-gradient(ellipse at 52% 48%,#245849 0%,#123c32 58%,#09261f 100%)':`url('${file}')`;document.body.style.backgroundSize='cover';document.body.style.backgroundPosition='center';localStorage.setItem('hastaDesktopWallpaper',file);document.querySelectorAll('[data-wallpaper]').forEach(button=>button.classList.toggle('active',button.dataset.wallpaper===file));};
    wallpaperButton.addEventListener('click',event=>{event.preventDefault();event.stopPropagation();wallpaperPicker.classList.toggle('open');});
    document.querySelectorAll('[data-wallpaper]').forEach(button=>button.addEventListener('click',()=>{applyWallpaper(button.dataset.wallpaper);wallpaperPicker.classList.remove('open');}));
    applyWallpaper('matte-green');
    const applyScreenTheme=theme=>{document.body.classList.remove('theme-gray','theme-green','theme-blue','theme-sofitel');document.body.classList.add('theme-'+theme);localStorage.setItem('voxScreenTheme',theme);document.querySelectorAll('[data-theme]').forEach(button=>button.classList.toggle('active',button.dataset.theme===theme));};
    document.querySelectorAll('[data-theme]').forEach(button=>button.addEventListener('click',()=>{applyScreenTheme(button.dataset.theme);wallpaperPicker.classList.remove('open');}));
    applyScreenTheme('sofitel');
    document.getElementById('logout-button').addEventListener('click',async event=>{event.preventDefault();try{if(authenticated){const payload=await apiFetch(endpoints.logout,{method:'POST',body:'{}'});csrfToken=payload.csrfToken;document.querySelector('meta[name="csrf-token"]').content=csrfToken;}}finally{accessToken=null;memberRecords=[];document.getElementById('start-menu').classList.remove('open');document.getElementById('login-password').value='';loginScreen.classList.remove('hidden');setTimeout(()=>loginUser.focus(),330);}});
    const updateClock = () => document.getElementById('desktop-clock').textContent = new Date().toLocaleTimeString('tr-TR',{hour:'2-digit',minute:'2-digit'});
    updateClock(); setInterval(updateClock, 30000);
    const appWindow = document.querySelector('.app');
    appWindow.style.height = '';
    const appMain = appWindow.querySelector('main');
    const centerWindow = () => {
      if (appWindow.hidden || appWindow.classList.contains('maximized') || appWindow.classList.contains('minimized')) return;
      const rect=appWindow.getBoundingClientRect(), workHeight=window.innerHeight-31;
      const width=Math.min(rect.width,window.innerWidth), height=Math.min(rect.height,workHeight);
      const left=Math.max(0,(window.innerWidth-width)/2), top=Math.max(0,(workHeight-height)/2);
      Object.assign(appWindow.style,{left:left+'px',top:top+'px',width:width+'px',height:height+'px',right:'auto'});
    };
    requestAnimationFrame(centerWindow);
    let normalWindowSize = null;
    let normalWindowStyle = null;
    const applyMaximizedScale = () => {
      if (!appWindow.classList.contains('maximized') || !normalWindowSize) return;
      const workWidth=window.innerWidth, workHeight=window.innerHeight-31;
      const scale=Math.max(1,Math.min(workWidth/normalWindowSize.width,workHeight/normalWindowSize.height));
      appMain.style.transform=`scale(${scale})`;
      appMain.style.width=(workWidth/scale)+'px'; appMain.style.height=(workHeight/scale)+'px';
    };
    const toggleMaximize = () => {
      const maximizing=!appWindow.classList.contains('maximized');
      if (maximizing) {
        const rect=appWindow.getBoundingClientRect(); normalWindowSize={width:rect.width,height:rect.height};
        normalWindowStyle={left:appWindow.style.left,top:appWindow.style.top,right:appWindow.style.right,width:appWindow.style.width,height:appWindow.style.height};
        appWindow.classList.add('maximized');
        Object.assign(appWindow.style,{left:'0px',top:'0px',right:'auto',width:window.innerWidth+'px',height:(window.innerHeight-31)+'px'});
        requestAnimationFrame(applyMaximizedScale);
      } else {
        appWindow.classList.remove('maximized'); appMain.style.transform=''; appMain.style.width=''; appMain.style.height='';
        if(normalWindowStyle) Object.assign(appWindow.style,normalWindowStyle);
        requestAnimationFrame(centerWindow);
      }
      document.getElementById('window-maximize').textContent=maximizing?'❐':'□';
    };
    document.getElementById('window-maximize').addEventListener('click', toggleMaximize);
    document.getElementById('window-titlebar').addEventListener('dblclick', event => { if (!event.target.closest('button')) toggleMaximize(); });
    document.getElementById('window-minimize').addEventListener('click', () => minimizeDesktopWindow(appWindow,document.getElementById('patient-task')));
    document.getElementById('window-close').addEventListener('click', () => appWindow.classList.add('minimized'));
    document.getElementById('patient-task').addEventListener('click', () => toggleTaskWindow(appWindow,document.getElementById('patient-task')));
    const titlebar = document.getElementById('window-titlebar');
    let dragState = null;
    titlebar.addEventListener('pointerdown', event => {
      if (event.target.closest('button') || appWindow.classList.contains('maximized')) return;
      const rect = appWindow.getBoundingClientRect();
      dragState = { x:event.clientX, y:event.clientY, left:rect.left, top:rect.top };
      titlebar.setPointerCapture(event.pointerId);
    });
    titlebar.addEventListener('pointermove', event => {
      if (!dragState) return;
      const maxLeft = Math.max(0, window.innerWidth - appWindow.offsetWidth);
      const maxTop = Math.max(0, window.innerHeight - 31 - appWindow.offsetHeight);
      const left = Math.min(maxLeft, Math.max(0, dragState.left + event.clientX - dragState.x));
      const top = Math.min(maxTop, Math.max(0, dragState.top + event.clientY - dragState.y));
      appWindow.style.left = left + 'px'; appWindow.style.top = top + 'px'; appWindow.style.right = 'auto';
    });
    const stopDragging = () => { dragState = null; };
    titlebar.addEventListener('pointerup', stopDragging); titlebar.addEventListener('pointercancel', stopDragging);
    const resizer = document.getElementById('window-resizer');
    let resizeState = null;
    resizer.addEventListener('pointerdown', event => {
      if (appWindow.classList.contains('maximized')) return;
      event.preventDefault(); document.querySelectorAll('.start-menu a.active').forEach(item=>item.classList.remove('active')); link.classList.add('active');
      const rect = appWindow.getBoundingClientRect();
      resizeState = { x:event.clientX, y:event.clientY, width:rect.width, height:rect.height, left:rect.left, top:rect.top };
      resizer.setPointerCapture(event.pointerId);
    });
    resizer.addEventListener('pointermove', event => {
      if (!resizeState) return;
      const maxWidth = window.innerWidth - resizeState.left;
      const maxHeight = window.innerHeight - 31 - resizeState.top;
      const width = Math.min(maxWidth, Math.max(560, resizeState.width + event.clientX - resizeState.x));
      const height = Math.min(maxHeight, Math.max(360, resizeState.height + event.clientY - resizeState.y));
      appWindow.style.width = width + 'px'; appWindow.style.height = height + 'px'; appWindow.style.right = 'auto';
    });
    const stopResizing = () => { resizeState = null; };
    resizer.addEventListener('pointerup', stopResizing); resizer.addEventListener('pointercancel', stopResizing);
    const frameEdgeSize = 7;
    let frameResizeState = null;
    const detectFrameEdges = event => {
      const rect = appWindow.getBoundingClientRect();
      return {
        left:event.clientX - rect.left <= frameEdgeSize,
        right:rect.right - event.clientX <= frameEdgeSize,
        top:event.clientY - rect.top <= frameEdgeSize,
        bottom:rect.bottom - event.clientY <= frameEdgeSize
      };
    };
    const edgeCursor = edges => {
      if ((edges.left && edges.top) || (edges.right && edges.bottom)) return 'nwse-resize';
      if ((edges.right && edges.top) || (edges.left && edges.bottom)) return 'nesw-resize';
      if (edges.left || edges.right) return 'ew-resize';
      if (edges.top || edges.bottom) return 'ns-resize';
      return '';
    };
    const contentHeightLimit = () => Math.min(window.innerHeight - 31, Math.ceil(document.getElementById('window-titlebar').offsetHeight + document.getElementById('patient-form').scrollHeight + 14));
    appWindow.addEventListener('pointerdown', event => {
      if (appWindow.classList.contains('maximized')) return;
      const edges = detectFrameEdges(event);
      if (!edgeCursor(edges)) return;
      event.preventDefault(); event.stopPropagation();
      const rect = appWindow.getBoundingClientRect();
      frameResizeState = { edges, x:event.clientX, y:event.clientY, left:rect.left, top:rect.top, width:rect.width, height:rect.height };
      appWindow.classList.add('frame-resizing'); appWindow.setPointerCapture(event.pointerId);
    }, true);
    appWindow.addEventListener('pointermove', event => {
      if (!frameResizeState) { if (!event.target.closest('input,select,textarea,button')) appWindow.style.cursor = edgeCursor(detectFrameEdges(event)); return; }
      const state = frameResizeState, dx = event.clientX - state.x, dy = event.clientY - state.y;
      let left = state.left, top = state.top, width = state.width, height = state.height;
      if (state.edges.right) width = Math.min(window.innerWidth - state.left, Math.max(560, state.width + dx));
      if (state.edges.bottom) height = Math.min(contentHeightLimit(), window.innerHeight - 31 - state.top, Math.max(360, state.height + dy));
      if (state.edges.left) { const fixedRight = Math.min(window.innerWidth, state.left + state.width); left = Math.max(0, Math.min(fixedRight - 560, state.left + dx)); width = fixedRight - left; }
      if (state.edges.top) { const fixedBottom = Math.min(window.innerHeight - 31, state.top + state.height); const wantedHeight=Math.min(contentHeightLimit(),Math.max(360,state.height-dy)); top=Math.max(0,fixedBottom-wantedHeight); height=fixedBottom-top; }
      width=Math.min(width,window.innerWidth-left); height=Math.min(height,window.innerHeight-31-top);
      appWindow.style.left=left+'px'; appWindow.style.top=top+'px'; appWindow.style.width=width+'px'; appWindow.style.height=height+'px'; appWindow.style.right='auto';
    }, true);
    const stopFrameResize = () => { const resized=!!frameResizeState; frameResizeState=null; appWindow.classList.remove('frame-resizing'); appWindow.style.cursor=''; if(resized) requestAnimationFrame(centerWindow); };
    appWindow.addEventListener('pointerup', stopFrameResize, true); appWindow.addEventListener('pointercancel', stopFrameResize, true);
    window.addEventListener('resize', () => {
      if (appWindow.classList.contains('maximized')) { applyMaximizedScale(); return; }
      const rect=appWindow.getBoundingClientRect();
      const width=Math.min(rect.width,window.innerWidth), height=Math.min(rect.height,window.innerHeight-31);
      const left=Math.max(0,Math.min(rect.left,window.innerWidth-width)), top=Math.max(0,Math.min(rect.top,window.innerHeight-31-height));
      Object.assign(appWindow.style,{left:left+'px',top:top+'px',width:width+'px',height:height+'px',right:'auto'}); requestAnimationFrame(centerWindow);
    });
    let desktopZ=40;
    const activateWindow = win => { desktopZ+=1; win.style.zIndex=desktopZ; document.querySelectorAll('.task-item').forEach(item=>item.classList.toggle('active',item.dataset.windowId===win.dataset.windowId)); };
    const activateTopVisibleWindow=()=>{const visible=[appWindow,...document.querySelectorAll('.aux-window')].filter(win=>!win.hidden&&!win.classList.contains('minimized')&&document.body.contains(win));const top=visible.sort((a,b)=>(Number(a.style.zIndex)||0)-(Number(b.style.zIndex)||0)).pop();if(top)activateWindow(top);else document.querySelectorAll('.task-item').forEach(item=>item.classList.remove('active'));};
    const minimizeDesktopWindow=(win,task)=>{if(win.classList.contains('window-motion'))return;const wr=win.getBoundingClientRect(),tr=task.getBoundingClientRect();win.dataset.restoreLeft=win.style.left;win.dataset.restoreTop=win.style.top;win.style.setProperty('--task-x',(tr.left-wr.left)+'px');win.style.setProperty('--task-y',(tr.top-wr.top)+'px');win.classList.add('window-motion');requestAnimationFrame(()=>win.classList.add('window-minimizing'));setTimeout(()=>{win.classList.add('minimized');win.hidden=true;win.classList.remove('window-motion','window-minimizing');win.style.removeProperty('--task-x');win.style.removeProperty('--task-y');task.classList.remove('active');activateTopVisibleWindow();},390);};
    const restoreDesktopWindow=(win,task)=>{if(win.classList.contains('window-motion'))return;win.hidden=false;win.classList.remove('minimized');const wr=win.getBoundingClientRect(),tr=task.getBoundingClientRect();win.style.transition='none';win.style.transform=`translate(${tr.left-wr.left}px,${tr.top-wr.top}px) scale(.16,.06)`;win.style.opacity='.12';requestAnimationFrame(()=>requestAnimationFrame(()=>{win.style.transition='';win.classList.add('window-motion');win.style.transform='';win.style.opacity='';activateWindow(win);setTimeout(()=>win.classList.remove('window-motion'),390);}));};
    const toggleTaskWindow=(win,task)=>{const hidden=win.hidden||win.classList.contains('minimized');if(hidden){restoreDesktopWindow(win,task);return;}if(task.classList.contains('active'))minimizeDesktopWindow(win,task);else activateWindow(win);};
    appWindow.dataset.windowId='patient'; appWindow.addEventListener('pointerdown',()=>activateWindow(appWindow)); document.getElementById('patient-task').dataset.windowId='patient';
    const setupPatientColumns=win=>{const table=win.querySelector('.patient-table'),headings=[...table.querySelectorAll('thead th')],tbody=table.tBodies[0],cols=[...table.querySelectorAll('colgroup col')];requestAnimationFrame(()=>{let total=0;headings.forEach((th,index)=>{const width=Math.round(th.getBoundingClientRect().width);if(cols[index])cols[index].style.width=width+'px';total+=width;});table.style.width=Math.max(table.parentElement.clientWidth,total)+'px';});headings.forEach((th,columnIndex)=>{const grip=document.createElement('span');grip.className='column-resizer';grip.title='Kolon genişliğini değiştir';th.appendChild(grip);let resize=null;grip.addEventListener('pointerdown',event=>{event.preventDefault();event.stopPropagation();resize={x:event.clientX,width:th.getBoundingClientRect().width,tableWidth:table.getBoundingClientRect().width};grip.setPointerCapture(event.pointerId);grip.classList.add('dragging');document.body.classList.add('column-resizing');});grip.addEventListener('pointermove',event=>{if(!resize)return;const newWidth=Math.max(55,resize.width+event.clientX-resize.x),difference=newWidth-resize.width;if(cols[columnIndex])cols[columnIndex].style.width=newWidth+'px';table.style.width=Math.max(table.parentElement.clientWidth,resize.tableWidth+difference)+'px';});const stopResize=()=>{resize=null;grip.classList.remove('dragging');document.body.classList.remove('column-resizing');};grip.addEventListener('pointerup',stopResize);grip.addEventListener('pointercancel',stopResize);const arrow=document.createElement('button');arrow.type='button';arrow.className='column-menu-button';arrow.textContent='▼';arrow.title=th.textContent+' seçenekleri';th.appendChild(arrow);arrow.addEventListener('click',event=>{event.stopPropagation();document.querySelectorAll('.column-context-menu').forEach(menu=>menu.remove());document.querySelectorAll('.column-menu-button.open').forEach(button=>button.classList.remove('open'));arrow.classList.add('open');const menu=document.createElement('div');menu.className='column-context-menu';const makeAction=(icon,text,direction)=>{const button=document.createElement('button');button.type='button';button.innerHTML=`<span>${icon}</span><span>${text}</span>`;button.addEventListener('click',()=>{const rows=[...tbody.rows];rows.sort((a,b)=>{const av=a.cells[columnIndex].textContent.trim(),bv=b.cells[columnIndex].textContent.trim();return av.localeCompare(bv,'tr',{numeric:true,sensitivity:'base'})*(direction==='asc'?1:-1);});rows.forEach(row=>tbody.appendChild(row));menu.remove();arrow.classList.remove('open');});return button;};menu.append(makeAction('A↧','Artan sırada sırala','asc'),makeAction('Z↥','Azalan sırada sırala','desc'));const separator=document.createElement('div');separator.className='menu-separator';menu.appendChild(separator);const columnsButton=document.createElement('button');columnsButton.type='button';columnsButton.className='columns-trigger';columnsButton.innerHTML='<span>▦ &nbsp; Kolonlar</span><span>▶</span>';const panel=document.createElement('div');panel.className='columns-panel';panel.hidden=true;headings.forEach((heading,index)=>{const label=document.createElement('label'),check=document.createElement('input');check.type='checkbox';check.checked=!heading.classList.contains('column-hidden');check.addEventListener('change',()=>{heading.classList.toggle('column-hidden',!check.checked);heading.style.display=check.checked?'':'none';[...table.rows].slice(1).forEach(row=>{if(row.cells[index])row.cells[index].style.display=check.checked?'':'none';});});label.append(check,document.createTextNode(heading.childNodes[0].textContent.trim()));panel.appendChild(label);});columnsButton.addEventListener('click',event=>{event.stopPropagation();panel.hidden=!panel.hidden;});menu.append(columnsButton,panel);document.body.appendChild(menu);const rect=arrow.getBoundingClientRect(),menuWidth=310;menu.style.left=Math.max(2,Math.min(innerWidth-menuWidth,rect.right-155))+'px';menu.style.top=Math.max(2,Math.min(innerHeight-310,rect.bottom))+'px';const close=event=>{if(!menu.contains(event.target)&&event.target!==arrow){menu.remove();arrow.classList.remove('open');document.removeEventListener('pointerdown',close);}};setTimeout(()=>document.addEventListener('pointerdown',close),0);});});};
    const memberRowsMarkup=()=>memberRecords.map(member=>`<tr data-member-id="${member.id}" data-search="${attr(`${member.memberNo} ${member.name} ${member.phone||''}`).toLocaleLowerCase('tr-TR')}" data-status="${attr(member.status)}"><td>${attr(member.memberNo)}</td><td>${attr(member.name)}</td><td>${attr(member.phone||'')}</td><td>${attr(member.membershipType)}</td><td>${attr(displayDate(member.validThrough))}</td><td><span class="status-badge ${member.status==='aktif'?'active':'passive'}">${member.status==='aktif'?'Aktif':'Pasif'}</span></td></tr>`).join('');
    const patientListMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="aux-content"><div class="aux-toolbar"><button data-list-action="refresh">🔄 Listele</button><button data-list-action="card">▣ Üye Kartı</button><button data-list-action="excel">▧ Excel'e Aktar</button></div><div class="patient-list-shell"><div class="patient-list-filters"><label>Üye Ara:<input type="search" data-patient-search placeholder="Üye no, ad veya telefon"></label><label>Durum:<span class="select-shell"><select data-patient-status><option value="">Tümü</option><option value="aktif">Aktif</option><option value="pasif">Pasif</option></select><span class="select-arrow">▼</span></span></label></div><div class="patient-grid-wrap"><table class="aux-grid patient-table"><colgroup><col style="width:85px"><col style="width:190px"><col style="width:125px"><col style="width:125px"><col style="width:130px"><col style="width:90px"></colgroup><thead><tr><th>Üye No</th><th>Ad Soyad</th><th>Telefon</th><th>Üyelik Türü</th><th>Geçerlilik Bitişi</th><th>Durum</th></tr></thead><tbody>${memberRowsMarkup()}</tbody></table></div><div class="list-pager"><button title="İlk sayfa">|◀</button><button title="Önceki sayfa">◀</button><span>Sayfa</span><input value="1" size="2"><span>/ 1</span><button title="Sonraki sayfa">▶</button><button title="Son sayfa">▶|</button><button data-list-action="refresh" title="Yenile">🔄</button><span class="list-count">Gösterilen ${memberRecords.length}</span></div></div></div>`;
    const memberCardPayloads={};
    const attr=value=>String(value??'').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    const memberCardMarkup=(title,member={})=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="member-card-content"><form class="member-card-form"><div class="member-card-toolbar"><button type="submit">💾 Kaydet</button><button type="button" data-member-action="close">✕ Kapat</button><span class="member-card-status" role="status"></span></div><div class="member-card-hint">Üye bilgilerini düzenleyip Kaydet düğmesine basın.</div>
      <fieldset class="member-card-section"><legend>KİŞİSEL BİLGİLER / PERSONAL INFORMATION</legend><div class="member-card-grid">
        <label>Üyelik No / Membership No</label><input name="memberNo" value="${attr(member.memberNo)}">
        <label>Adı Soyadı / Name Last Name</label><input name="name" value="${attr(member.name)}" required>
        <label>TC Kimlik No / Identity Number</label><input name="identity" value="${attr(member.identity)}" maxlength="11">
        <label>Mesleği / Occupation</label><input name="occupation" value="${attr(member.occupation)}">
        <label>Doğum Tarihi / Date Of Birth</label><input name="birthDate" type="date" value="${attr(member.birthDate)}">
        <label>Cep Tel / Mobile Phone</label><input name="phone" value="${attr(member.phone)}">
        <label class="wide-label">Adres / Address</label><textarea class="wide-field" name="address">${attr(member.address)}</textarea>
        <label>E-Posta / E-mail</label><input name="email" type="email" value="${attr(member.email)}">
        <label>Acil Durumda Aranacak Kişi</label><input name="emergencyName" value="${attr(member.emergencyName)}">
        <label>Emergency Contact – Phone</label><input name="emergencyPhone" value="${attr(member.emergencyPhone)}">
        <label>Durum / Status</label><select name="status"><option value="aktif" ${member.status==='aktif'?'selected':''}>Aktif</option><option value="pasif" ${member.status==='pasif'?'selected':''}>Pasif</option></select>
      </div></fieldset>
      <fieldset class="member-card-section"><legend>ÜYELİK BİLGİLERİ / MEMBERSHIP INFO</legend><div class="member-card-grid">
        <label>Üyelik Türü / Membership Type</label><input name="membershipType" value="${attr(member.membershipType)}">
        <label>Süresi / Duration (Ay)</label><input name="durationMonths" type="number" min="0" value="${attr(member.durationMonths)}">
        <label>Başlangıç / Valid From</label><input name="validFrom" type="date" value="${attr(member.validFrom)}">
        <label>Bitiş / Through</label><input name="validThrough" type="date" value="${attr(member.validThrough)}">
      </div></fieldset>
      <fieldset class="member-card-section"><legend>ÖDEME TAAHHÜTNAMESİ / PAYMENT TERMS</legend><div class="member-card-grid">
        <label>Ödeme Şekli / Payment Type</label><select name="paymentType"><option ${member.paymentType==='Nakit'?'selected':''}>Nakit</option><option ${member.paymentType==='Kredi Kartı'?'selected':''}>Kredi Kartı</option><option ${member.paymentType==='Havale'?'selected':''}>Havale</option></select>
        <label>Sözleşme Bedeli / Contract Amount</label><input name="contractAmount" type="number" min="0" step="0.01" value="${attr(member.contractAmount)}">
        <label class="wide-label">Fatura Adresi / Invoice Address</label><textarea class="wide-field" name="invoiceAddress">${attr(member.invoiceAddress)}</textarea>
      </div></fieldset></form></div>`;
    let employeeRecords=[],occupationRecords=[],workGroupRecords=[];
    const employeeCardPayloads={};
    const employeeName=employee=>`${employee.first_name||''} ${employee.last_name||''}`.trim();
    const employeeRowsMarkup=()=>employeeRecords.map(employee=>`<tr data-employee-id="${employee.id}" data-search="${attr(`${employee.id} ${employeeName(employee)} ${employee.registry_no||''} ${employee.personnel_no||''}`.toLocaleLowerCase('tr-TR'))}"><td>${employee.id}</td><td>${attr(employee.first_name)}</td><td>${attr(employee.last_name)}</td><td>${attr(employee.registry_no||'')}</td><td>${attr(employee.personnel_no||'')}</td><td>${attr(displayDate(employee.hire_date))}</td><td><span class="status-badge ${employee.status==='aktif'?'active':'passive'}">${employee.status==='aktif'?'Aktif':employee.status==='ayrıldı'?'Ayrıldı':'Yasaklı'}</span></td></tr>`).join('');
    const employeeListMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="aux-content"><div class="aux-toolbar"><button type="button" data-employee-action="new">⊕ Yeni Personel</button><button type="button" data-employee-action="refresh">🔄 Listele</button><span class="shift-message" role="status"></span></div><div class="employee-list-shell"><div class="patient-list-filters"><label>Personel Ara:<input type="search" data-employee-search placeholder="ID, ad, sicil veya personel no"></label><label>Durum:<span class="select-shell"><select data-employee-status><option value="">Tümü</option><option value="aktif">Aktif</option><option value="ayrıldı">Ayrıldı</option><option value="yasaklı">Yasaklı</option></select><span class="select-arrow">▼</span></span></label></div><div class="employee-grid-wrap"><table class="aux-grid employee-table"><thead><tr><th>ID</th><th>Ad</th><th>Soyad</th><th>Sicil No</th><th>Personel No</th><th>Giriş Tarihi</th><th>Durum</th></tr></thead><tbody><tr><td colspan="7">Personel listesi yükleniyor…</td></tr></tbody></table></div><div class="list-pager"><span class="list-count">Gösterilen 0</span></div></div></div>`;
    const employeeCardMarkup=(title,employee={})=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="employee-card-content"><form class="employee-card-form"><div class="employee-card-toolbar"><button type="submit">💾 Kaydet</button><span class="employee-card-status" role="status"></span></div><div class="employee-main"><aside class="employee-photo-panel"><div class="employee-photo">${employee.photo_url?`<img src="${attr(employee.photo_url)}" alt="Personel fotoğrafı">`:'👤'}</div><label class="employee-photo-upload">📷 Fotoğraf Seç<input type="file" name="photo_file" accept="image/jpeg,image/png,image/webp"></label><div class="employee-side-tabs"><button type="button" class="active" data-employee-tab="personal">Kişisel Bilgileri</button><button type="button" data-employee-tab="contact">İletişim Bilgileri</button><button type="button">Belgeler</button></div></aside><div><div class="employee-fields employee-tab-panel" data-employee-panel="personal"><label>Ad</label><input name="first_name" value="${attr(employee.first_name)}" required><label>Soyad</label><input name="last_name" value="${attr(employee.last_name)}" required><label>Personel No</label><input name="personnel_no" value="${attr(employee.personnel_no)}"><label>Mesleği</label><select name="occupation_id"><option value="">Seçiniz</option>${occupationRecords.map(item=>`<option value="${item.id}" ${Number(employee.occupation_id)===Number(item.id)?'selected':''}>${attr(item.name)}</option>`).join('')}</select><label>Çalışma Grubu</label><select name="work_group_id"><option value="">Seçiniz</option>${workGroupRecords.map(item=>`<option value="${item.id}" ${Number(employee.work_group_id)===Number(item.id)?'selected':''}>${attr(item.name)}</option>`).join('')}</select><label>Giriş Tarihi</label><input name="hire_date" type="date" value="${attr(employee.hire_date)}"><label>Çıkış Tarihi</label><input name="termination_date" type="date" value="${attr(employee.termination_date)}"><label>Doğum Tarihi</label><input name="birth_date" type="date" value="${attr(employee.birth_date)}"><label>Kan Grubu</label><select name="blood_group"><option value="">Seçiniz</option>${['A+','A-','B+','B-','AB+','AB-','0+','0-'].map(value=>`<option ${employee.blood_group===value?'selected':''}>${value}</option>`).join('')}</select><label>Cinsiyet</label><select name="gender"><option value="">Seçiniz</option>${['Kadın','Erkek','Belirtmek İstemiyor'].map(value=>`<option ${employee.gender===value?'selected':''}>${value}</option>`).join('')}</select><label>Durum</label><select name="status"><option value="aktif" ${!employee.status||employee.status==='aktif'?'selected':''}>Aktif</option><option value="ayrıldı" ${employee.status==='ayrıldı'?'selected':''}>Ayrıldı</option><option value="yasaklı" ${employee.status==='yasaklı'?'selected':''}>Yasaklı</option></select></div><div class="employee-fields employee-contact-fields employee-tab-panel" data-employee-panel="contact" hidden><div class="employee-card-section-title">✉ İletişim Bilgileri</div><label>Telefon 1</label><input name="phone" type="tel" value="${attr(employee.phone)}"><label>Cep Telefonu</label><input name="mobile_phone" type="tel" value="${attr(employee.mobile_phone)}"><label>E-mail</label><input name="email" type="email" value="${attr(employee.email)}"><label>İl</label><input name="city" value="${attr(employee.city)}"><label>İlçe</label><input name="district" value="${attr(employee.district)}"><label>Adres</label><textarea name="address">${attr(employee.address)}</textarea></div></div></div></form></div>`;
    const renderEmployeeRows=win=>{const body=win.querySelector('.employee-table tbody');body.innerHTML=employeeRecords.length?employeeRowsMarkup():'<tr><td colspan="7">Kayıtlı personel bulunamadı.</td></tr>';const count=win.querySelector('.list-count');if(count)count.textContent=`Gösterilen ${employeeRecords.length}`;};
    const loadEmployeeList=async win=>{const status=win.querySelector('.shift-message');status.textContent='Personel yükleniyor…';try{const payload=await apiFetch(endpoints.employees);employeeRecords=payload.data;renderEmployeeRows(win);status.textContent=`${employeeRecords.length} personel kaydı`;}catch(error){status.textContent=error.message;}};
    const setupEmployeeListWindow=win=>{const search=win.querySelector('[data-employee-search]'),statusFilter=win.querySelector('[data-employee-status]');const filter=()=>{const query=search.value.toLocaleLowerCase('tr-TR');let shown=0;win.querySelectorAll('.employee-table tbody tr[data-employee-id]').forEach(row=>{const employee=employeeRecords.find(item=>String(item.id)===row.dataset.employeeId),visible=(!query||row.dataset.search.includes(query))&&(!statusFilter.value||employee?.status===statusFilter.value);row.hidden=!visible;if(visible)shown++;});win.querySelector('.list-count').textContent=`Gösterilen ${shown}`;};search.oninput=filter;statusFilter.onchange=filter;win.querySelector('[data-employee-action="new"]').onclick=()=>openEmployeeCard();win.querySelector('[data-employee-action="refresh"]').onclick=()=>loadEmployeeList(win);win.querySelector('.employee-table tbody').addEventListener('click',event=>{const row=event.target.closest('tr[data-employee-id]');if(row)openEmployeeCard(Number(row.dataset.employeeId));});loadEmployeeList(win);};
    const openEmployeeCard=async employeeId=>{try{const [employeePayload,occupationsPayload,workGroupsPayload]=await Promise.all([employeeId?apiFetch(`${endpoints.employees}/${employeeId}`):Promise.resolve({data:{status:'aktif'}}),apiFetch(endpoints.occupations),apiFetch(endpoints.workGroups)]),employee=employeePayload.data,id=employeeId?`employee-${employeeId}`:'employee-new';occupationRecords=occupationsPayload.data;workGroupRecords=workGroupsPayload.data;employeeCardPayloads[id]=employee;openAuxWindow(id,employeeId?`Personel Kartı • ${employeeName(employee)}`:'Yeni Personel Kartı');}catch(error){alert(error.message);}};
    const setupEmployeeCardWindow=(win,id)=>{
      const form=win.querySelector('.employee-card-form'),status=win.querySelector('.employee-card-status'),photoInput=form.elements.photo_file,photoBox=win.querySelector('.employee-photo');
      win.querySelectorAll('[data-employee-tab]').forEach(button=>button.onclick=()=>{
        win.querySelectorAll('[data-employee-tab]').forEach(item=>item.classList.toggle('active',item===button));
        win.querySelectorAll('[data-employee-panel]').forEach(panel=>panel.hidden=panel.dataset.employeePanel!==button.dataset.employeeTab);
      });
      photoInput.onchange=()=>{
        const file=photoInput.files[0];
        if(!file)return;
        if(file.size>5*1024*1024){status.textContent='Fotoğraf en fazla 5 MB olabilir.';photoInput.value='';return;}
        const url=URL.createObjectURL(file);
        photoBox.innerHTML=`<img src="${url}" alt="Seçilen personel fotoğrafı">`;
        status.textContent='Fotoğraf kaydetmeye hazır.';
      };
      form.addEventListener('submit',async event=>{
        event.preventDefault();
        const employee=employeeCardPayloads[id],values=Object.fromEntries(new FormData(form));
        delete values.photo_file;
        status.textContent='Kaydediliyor…';
        try{
          const payload=await apiFetch(employee.id?`${endpoints.employees}/${employee.id}`:endpoints.employees,{method:employee.id?'PUT':'POST',body:JSON.stringify(values)});
          Object.assign(employee,payload.data);
          if(photoInput.files[0]){
            status.textContent='Fotoğraf yükleniyor…';
            const photoData=new FormData();
            photoData.append('photo',photoInput.files[0]);
            const photoPayload=await apiFetch(`${endpoints.employees}/${employee.id}/photo`,{method:'POST',body:photoData});
            Object.assign(employee,photoPayload.data);
            photoInput.value='';
          }
          status.textContent='Personel kaydı ve fotoğrafı kaydedildi.';
          const listWindow=document.querySelector('.aux-window[data-window-id="staff"]');
          if(listWindow)await loadEmployeeList(listWindow);
        }catch(error){status.textContent=error.message;}
      });
    };
    let workShiftRecords=[];
    const shortTime=value=>String(value||'').slice(0,5);
    const workShiftRowsMarkup=()=>workShiftRecords.map(shift=>`<tr data-shift-id="${shift.id}"><td>${shift.id}</td><td>${attr(shortTime(shift.start_time))}-${attr(shortTime(shift.end_time))}</td><td><div class="shift-actions"><button type="button" class="shift-edit" data-shift-action="edit">✎ Düzenle</button><button type="button" class="shift-delete" data-shift-action="delete">⌫ Sil</button></div></td></tr>`).join('');
    const definitionPanelMarkup=(type,title)=>`<section class="shift-panel setup-panel" data-setup-panel="${type}" hidden><div class="aux-toolbar"><button type="button" data-definition-action="new">⊕ Yeni ${title}</button><button type="button" data-definition-action="refresh">🔄 Yenile</button><span class="definition-message shift-message" role="status"></span></div><div class="shift-grid-wrap"><table class="aux-grid definition-table"><thead><tr><th>Kod</th><th>${title}</th><th>İşlemler</th></tr></thead><tbody><tr><td colspan="3">Kayıtlar yükleniyor…</td></tr></tbody></table></div></section>`;
    const setupMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="aux-content"><div class="setup-tabs"><button type="button" class="setup-tab active" data-setup-tab="shifts">Mesai Tanımları</button><button type="button" class="setup-tab" data-setup-tab="business-hours">Çalışma Saatleri</button><button type="button" class="setup-tab" data-setup-tab="occupations">Meslekler</button><button type="button" class="setup-tab" data-setup-tab="work-groups">Çalışma Grupları</button></div><section class="shift-panel setup-panel" data-setup-panel="shifts"><div class="aux-toolbar"><button type="button" data-shift-action="new">⊕ Yeni Mesai</button><button type="button" data-shift-action="refresh">🔄 Yenile</button><span class="shift-message" role="status"></span></div><div class="shift-grid-wrap"><table class="aux-grid shift-table"><thead><tr><th>Kod</th><th>Mesai Saatleri</th><th>İşlemler</th></tr></thead><tbody><tr><td colspan="3">Mesailer yükleniyor…</td></tr></tbody></table></div></section><section class="business-hours-panel setup-panel" data-setup-panel="business-hours" hidden><div class="aux-toolbar"><button type="button" data-business-hours-action="save">💾 Çalışma Saatlerini Kaydet</button><button type="button" data-business-hours-action="refresh">🔄 Yenile</button><span class="business-hours-message shift-message" role="status"></span></div><div class="business-hours-grid-wrap"><table class="aux-grid business-hours-table"><thead><tr><th>Gün</th><th>Açılış</th><th>Kapanış</th><th>Kapalı</th></tr></thead><tbody><tr><td colspan="4">Çalışma saatleri yükleniyor…</td></tr></tbody></table></div></section>${definitionPanelMarkup('occupations','Meslek')}${definitionPanelMarkup('work-groups','Çalışma Grubu')}</div>`;
    const renderWorkShiftRows=win=>{const body=win.querySelector('.shift-table tbody');body.innerHTML=workShiftRecords.length?workShiftRowsMarkup():'<tr><td colspan="3">Tanımlı mesai bulunamadı.</td></tr>';};
    const editWorkShiftRow=(win,row,shift=null)=>{const isNew=!shift;row.dataset.shiftId=isNew?'new':shift.id;row.innerHTML=`<td><input data-shift-field="id" type="number" min="1" value="${attr(shift?.id)}" ${isNew?'':'disabled'} aria-label="Mesai kodu"></td><td><div class="shift-actions"><input data-shift-field="start_time" type="time" value="${attr(shortTime(shift?.start_time))}" aria-label="Başlangıç saati"><span>–</span><input data-shift-field="end_time" type="time" value="${attr(shortTime(shift?.end_time))}" aria-label="Bitiş saati"></div></td><td><div class="shift-actions"><button type="button" class="shift-save" data-shift-action="save">💾 Kaydet</button><button type="button" data-shift-action="cancel">✕ Vazgeç</button></div></td>`;row.querySelector('[data-shift-field="id"]').focus();};
    const setupWorkShiftWindow=async win=>{const message=win.querySelector('.shift-message'),body=win.querySelector('.shift-table tbody');const refresh=async()=>{message.textContent='Mesailer yükleniyor…';try{const payload=await apiFetch(endpoints.workShifts);workShiftRecords=payload.data;renderWorkShiftRows(win);message.textContent=`${workShiftRecords.length} mesai tanımı`;}catch(error){message.textContent=error.message;}};win.querySelector('[data-shift-action="refresh"]').onclick=refresh;win.querySelector('[data-shift-action="new"]').onclick=()=>{if(body.querySelector('[data-shift-id="new"]'))return;const row=document.createElement('tr');body.prepend(row);editWorkShiftRow(win,row);};body.addEventListener('click',async event=>{const button=event.target.closest('[data-shift-action]');if(!button)return;const row=button.closest('tr'),action=button.dataset.shiftAction,record=workShiftRecords.find(item=>String(item.id)===row.dataset.shiftId);if(action==='edit')editWorkShiftRow(win,row,record);if(action==='cancel')renderWorkShiftRows(win);if(action==='save'){const data={id:Number(row.querySelector('[data-shift-field="id"]').value),start_time:row.querySelector('[data-shift-field="start_time"]').value,end_time:row.querySelector('[data-shift-field="end_time"]').value};message.textContent='Kaydediliyor…';try{await apiFetch(record?`${endpoints.workShifts}/${record.id}`:endpoints.workShifts,{method:record?'PUT':'POST',body:JSON.stringify(data)});await refresh();message.textContent='Mesai kaydedildi.';}catch(error){message.textContent=error.message;}}if(action==='delete'&&record&&confirm(`${record.id} numaralı ${shortTime(record.start_time)}-${shortTime(record.end_time)} mesaisini silmek istiyor musunuz?`)){message.textContent='Siliniyor…';try{await apiFetch(`${endpoints.workShifts}/${record.id}`,{method:'DELETE'});await refresh();message.textContent='Mesai silindi.';}catch(error){message.textContent=error.message;}}});await refresh();};
    const businessDayNames=['Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi','Pazar'];
    const renderBusinessHours=(win,hours)=>{
      const byDay=new Map(hours.map(hour=>[Number(hour.day_of_week),hour]));
      win.querySelector('.business-hours-table tbody').innerHTML=businessDayNames.map((dayName,index)=>{
        const day=index+1,hour=byDay.get(day)||{day_of_week:day,opening_time:'09:00',closing_time:'22:00',is_closed:false},closed=Boolean(hour.is_closed);
        return `<tr data-business-day="${day}" class="${closed?'is-closed':''}"><th>${dayName}</th><td><input type="time" data-business-field="opening_time" value="${attr(shortTime(hour.opening_time)||'09:00')}" ${closed?'disabled':''} aria-label="${dayName} açılış saati"></td><td><input type="time" data-business-field="closing_time" value="${attr(shortTime(hour.closing_time)||'22:00')}" ${closed?'disabled':''} aria-label="${dayName} kapanış saati"></td><td><label><input type="checkbox" data-business-field="is_closed" ${closed?'checked':''}> Kapalı</label></td></tr>`;
      }).join('');
    };
    const setupBusinessHoursWindow=win=>{
      const message=win.querySelector('.business-hours-message'),body=win.querySelector('.business-hours-table tbody');
      const refresh=async()=>{message.textContent='Çalışma saatleri yükleniyor…';try{const payload=await apiFetch(endpoints.businessHours);renderBusinessHours(win,payload.data);message.textContent='7 günün çalışma saatleri yüklendi.';}catch(error){message.textContent=error.message;}};
      win.querySelector('[data-business-hours-action="refresh"]').onclick=refresh;
      win.querySelector('[data-business-hours-action="save"]').onclick=async()=>{const hours=[...body.querySelectorAll('[data-business-day]')].map(row=>{const closed=row.querySelector('[data-business-field="is_closed"]').checked;return {day_of_week:Number(row.dataset.businessDay),opening_time:closed?null:row.querySelector('[data-business-field="opening_time"]').value,closing_time:closed?null:row.querySelector('[data-business-field="closing_time"]').value,is_closed:closed};});message.textContent='Çalışma saatleri kaydediliyor…';try{const payload=await apiFetch(endpoints.businessHours,{method:'PUT',body:JSON.stringify({hours})});renderBusinessHours(win,payload.data);message.textContent='Çalışma saatleri kaydedildi.';}catch(error){message.textContent=error.message;}};
      body.addEventListener('change',event=>{const checkbox=event.target.closest('[data-business-field="is_closed"]');if(!checkbox)return;const row=checkbox.closest('tr'),closed=checkbox.checked;row.classList.toggle('is-closed',closed);row.querySelectorAll('input[type="time"]').forEach(input=>input.disabled=closed);});
      return refresh();
    };
    const setupDefinitionWindow=(win,type,title,endpoint)=>{
      const panel=win.querySelector(`[data-setup-panel="${type}"]`),message=panel.querySelector('.definition-message'),body=panel.querySelector('.definition-table tbody');
      let records=[];
      const render=()=>{body.innerHTML=records.length?records.map(record=>`<tr data-definition-id="${record.id}"><td>${record.id}</td><td>${attr(record.name)}</td><td><div class="shift-actions"><button type="button" data-definition-action="edit">✎ Düzenle</button><button type="button" class="shift-delete" data-definition-action="delete">⌫ Sil</button></div></td></tr>`).join(''):'<tr><td colspan="3">Tanımlı kayıt bulunamadı.</td></tr>';};
      const edit=(row,record=null)=>{row.dataset.definitionId=record?.id||'new';row.innerHTML=`<td>${record?.id||'Yeni'}</td><td><input data-definition-field="name" value="${attr(record?.name)}" maxlength="100" aria-label="${title}"></td><td><div class="shift-actions"><button type="button" class="shift-save" data-definition-action="save">💾 Kaydet</button><button type="button" data-definition-action="cancel">✕ Vazgeç</button></div></td>`;row.querySelector('input').focus();};
      const refresh=async()=>{message.textContent='Kayıtlar yükleniyor…';try{const payload=await apiFetch(endpoint);records=payload.data;render();message.textContent=`${records.length} kayıt`;}catch(error){message.textContent=error.message;}};
      panel.querySelector('[data-definition-action="refresh"]').onclick=refresh;
      panel.querySelector('[data-definition-action="new"]').onclick=()=>{if(body.querySelector('[data-definition-id="new"]'))return;const row=document.createElement('tr');body.prepend(row);edit(row);};
      body.addEventListener('click',async event=>{const button=event.target.closest('[data-definition-action]');if(!button)return;const row=button.closest('tr'),action=button.dataset.definitionAction,record=records.find(item=>String(item.id)===row.dataset.definitionId);if(action==='edit')edit(row,record);if(action==='cancel')render();if(action==='save'){const name=row.querySelector('[data-definition-field="name"]').value.trim();message.textContent='Kaydediliyor…';try{await apiFetch(record?`${endpoint}/${record.id}`:endpoint,{method:record?'PUT':'POST',body:JSON.stringify({name})});await refresh();message.textContent=`${title} kaydedildi.`;}catch(error){message.textContent=error.message;}}if(action==='delete'&&record&&confirm(`${record.name} kaydını silmek istiyor musunuz?`)){message.textContent='Siliniyor…';try{await apiFetch(`${endpoint}/${record.id}`,{method:'DELETE'});await refresh();message.textContent=`${title} silindi.`;}catch(error){message.textContent=error.message;}}});
      return refresh();
    };
    const setupSetupWindow=win=>{
      setupWorkShiftWindow(win);
      const loaded={};
      win.querySelectorAll('[data-setup-tab]').forEach(tab=>tab.onclick=()=>{
        win.querySelectorAll('[data-setup-tab]').forEach(item=>item.classList.toggle('active',item===tab));
        win.querySelectorAll('[data-setup-panel]').forEach(panel=>panel.hidden=panel.dataset.setupPanel!==tab.dataset.setupTab);
        if(loaded[tab.dataset.setupTab])return;
        loaded[tab.dataset.setupTab]=true;
        if(tab.dataset.setupTab==='business-hours')setupBusinessHoursWindow(win);
        if(tab.dataset.setupTab==='occupations')setupDefinitionWindow(win,'occupations','Meslek',endpoints.occupations);
        if(tab.dataset.setupTab==='work-groups')setupDefinitionWindow(win,'work-groups','Çalışma Grubu',endpoints.workGroups);
      });
    };
    let scheduleWeekStart='';
    const localIso=date=>`${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(date.getDate()).padStart(2,'0')}`;
    const mondayOf=date=>{const result=new Date(date.getFullYear(),date.getMonth(),date.getDate(),12),day=(result.getDay()+6)%7;result.setDate(result.getDate()-day);return result;};
    const isoWeekNumber=date=>{const target=new Date(Date.UTC(date.getFullYear(),date.getMonth(),date.getDate())),day=target.getUTCDay()||7;target.setUTCDate(target.getUTCDate()+4-day);const yearStart=new Date(Date.UTC(target.getUTCFullYear(),0,1));return Math.ceil((((target-yearStart)/86400000)+1)/7);};
    const scheduleMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="schedule-shell"><div class="schedule-print-header"><img class="schedule-print-logo" src="{{ asset('Sofitelspa-transparent.png') }}" alt="Sofitel Spa"><div class="schedule-print-title"><h1>HAFTALIK ÇALIŞMA PROGRAMI</h1><span class="schedule-print-week-label">Hafta yükleniyor…</span></div></div><div class="schedule-toolbar"><button type="button" data-schedule-action="previous">◀ Önceki Hafta</button><button type="button" data-schedule-action="today">Bu Hafta</button><strong class="schedule-week-label">Hafta yükleniyor…</strong><label>Çalışma Grubu <select data-schedule-group><option value="">Tüm Gruplar</option></select></label><button type="button" data-schedule-action="next">Sonraki Hafta ▶</button><button type="button" data-schedule-action="save">💾 Haftayı Kaydet</button><button type="button" data-schedule-action="print">🖨 Yazdır</button><span class="shift-message" role="status"></span></div><div class="schedule-grid-wrap"><table class="schedule-table"><tbody><tr><td class="schedule-empty">Personel ve mesailer yükleniyor…</td></tr></tbody></table></div></div>`;
    const scheduleChoice=(assignment,shifts,businessHour)=>{const value=assignment?.work_shift_id?`shift:${assignment.work_shift_id}`:(assignment?.status||''),availableShifts=businessHour&&!businessHour.is_closed?shifts.filter(shift=>shortTime(shift.start_time)>=shortTime(businessHour.opening_time)&&shortTime(shift.end_time)<=shortTime(businessHour.closing_time)):[];return `<option value="">— Seçiniz —</option>${availableShifts.map(shift=>`<option value="shift:${shift.id}" ${value===`shift:${shift.id}`?'selected':''}>${attr(shortTime(shift.start_time))}-${attr(shortTime(shift.end_time))}</option>`).join('')}<option value="off" ${value==='off'?'selected':''}>OFF</option><option value="izin" ${value==='izin'?'selected':''}>İZİN</option><option value="raporlu" ${value==='raporlu'?'selected':''}>RAPORLU</option>`;};
    const renderSchedule=(win,data)=>{scheduleWeekStart=data.week_start;const start=new Date(`${data.week_start}T12:00:00`),days=Array.from({length:7},(_,index)=>{const day=new Date(start);day.setDate(start.getDate()+index);return day;}),dayNames=['Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi','Pazar'],businessHourMap=new Map((data.business_hours||[]).map(hour=>[Number(hour.day_of_week),hour])),assignmentMap=new Map(data.assignments.map(item=>[`${item.employee_id}:${String(item.work_date).slice(0,10)}`,item])),groupSelect=win.querySelector('[data-schedule-group]'),selectedGroup=groupSelect.value,groups=[...new Map(data.employees.filter(employee=>employee.work_group).map(employee=>[String(employee.work_group.id),employee.work_group])).values()].sort((a,b)=>a.name.localeCompare(b.name,'tr')),employees=data.employees.filter(employee=>!selectedGroup||(selectedGroup==='ungrouped'?!employee.work_group_id:String(employee.work_group_id)===selectedGroup)),weekLabel=`${isoWeekNumber(start)}. Hafta • ${displayDate(localIso(days[0]))} – ${displayDate(localIso(days[6]))}`;groupSelect.innerHTML=`<option value="">Tüm Gruplar</option>${groups.map(group=>`<option value="${group.id}" ${selectedGroup===String(group.id)?'selected':''}>${attr(group.name)}</option>`).join('')}<option value="ungrouped" ${selectedGroup==='ungrouped'?'selected':''}>Grupsuz Personel</option>`;win.querySelector('.schedule-week-label').textContent=weekLabel;win.querySelector('.schedule-print-week-label').textContent=selectedGroup?`${weekLabel} • ${attr(groupSelect.options[groupSelect.selectedIndex].text)}`:weekLabel;const head=`<thead><tr><th>PERSONEL</th>${days.map((day,index)=>`<th><span class="schedule-day-name">${dayNames[index]}</span><span class="schedule-day-date">${displayDate(localIso(day))}</span></th>`).join('')}</tr></thead>`,employeeRow=employee=>`<tr><th>${attr(employeeName(employee))}</th>${days.map(day=>{const date=localIso(day),dayOfWeek=((day.getDay()+6)%7)+1,assignment=assignmentMap.get(`${employee.id}:${date}`),state=assignment?.status?` state-${assignment.status}`:'';return `<td class="schedule-cell${state}"><select data-employee-id="${employee.id}" data-work-date="${date}" aria-label="${attr(employeeName(employee))} ${displayDate(date)}">${scheduleChoice(assignment,data.work_shifts,businessHourMap.get(dayOfWeek))}</select></td>`;}).join('')}</tr>`,grouped=new Map();employees.forEach(employee=>{const key=employee.work_group?.name||'Grupsuz Personel';if(!grouped.has(key))grouped.set(key,[]);grouped.get(key).push(employee);});const body=employees.length?[...grouped.entries()].map(([groupName,members])=>`<tr class="schedule-group-row"><th colspan="8">${attr(groupName)}</th></tr>${members.map(employeeRow).join('')}`).join(''):`<tr><td colspan="8" class="schedule-empty">Bu çalışma grubunda aktif personel bulunamadı.</td></tr>`;win.querySelector('.schedule-table').innerHTML=head+`<tbody>${body}</tbody>`;};
    const setupScheduleWindow=win=>{const message=win.querySelector('.shift-message');let scheduleData=null;const load=async week=>{message.textContent='Plan yükleniyor…';try{const payload=await apiFetch(`${endpoints.employeeSchedules}?week_start=${week}`);scheduleData=payload.data;renderSchedule(win,scheduleData);message.textContent=`${payload.data.employees.length} personel • ${payload.data.work_shifts.length} mesai`;}catch(error){message.textContent=error.message;}};const move=days=>{const date=new Date(`${scheduleWeekStart}T12:00:00`);date.setDate(date.getDate()+days);load(localIso(date));};win.querySelector('[data-schedule-group]').onchange=()=>scheduleData&&renderSchedule(win,scheduleData);win.querySelector('[data-schedule-action="previous"]').onclick=()=>move(-7);win.querySelector('[data-schedule-action="next"]').onclick=()=>move(7);win.querySelector('[data-schedule-action="today"]').onclick=()=>load(localIso(mondayOf(new Date())));win.querySelector('[data-schedule-action="print"]').onclick=()=>window.print();win.querySelector('[data-schedule-action="save"]').onclick=async()=>{const selects=[...win.querySelectorAll('.schedule-cell select')],employee_ids=[...new Set(selects.map(select=>Number(select.dataset.employeeId)))],assignments=selects.map(select=>{const value=select.value;return {employee_id:Number(select.dataset.employeeId),work_date:select.dataset.workDate,work_shift_id:value.startsWith('shift:')?Number(value.slice(6)):null,status:value&&!value.startsWith('shift:')?value:null};});message.textContent='Hafta kaydediliyor…';try{const payload=await apiFetch(endpoints.employeeSchedules,{method:'PUT',body:JSON.stringify({week_start:scheduleWeekStart,employee_ids,assignments})});scheduleData=payload.data;renderSchedule(win,scheduleData);message.textContent='Seçili çalışma grubunun haftalık programı kaydedildi.';}catch(error){message.textContent=error.message;}};win.querySelector('.schedule-grid-wrap').addEventListener('change',event=>{const select=event.target.closest('.schedule-cell select');if(!select)return;select.parentElement.className=`schedule-cell${['off','izin','raporlu'].includes(select.value)?` state-${select.value}`:''}`;});load(localIso(mondayOf(new Date())));};
    const auxMarkup = (id,title) => id==='listeler'?patientListMarkup(title):id==='staff'?employeeListMarkup(title):id==='shifts'?scheduleMarkup(title):id==='setup'?setupMarkup(title):id.startsWith('employee-')?employeeCardMarkup(title,employeeCardPayloads[id]):id.startsWith('member-')?memberCardMarkup(title,memberCardPayloads[id]):`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="aux-content"><div class="aux-toolbar"><button>▣ Listele</button><button>⊕ Yeni</button><button>▱ Kopyala</button><button>▧ Excel</button></div><table class="aux-grid"><thead><tr><th>Kayıt No</th><th>Açıklama</th><th>Durum</th></tr></thead><tbody><tr><td colspan="3">Kayıtları görüntülemek için “Listele” düğmesini kullanın.</td></tr></tbody></table></div>`;
    const openAuxWindow = (id,title) => {
      let win=document.querySelector(`.aux-window[data-window-id="${id}"]`);
      if(win){ win.hidden=false; activateWindow(win); return; }
      win=document.createElement('section'); win.className='aux-window'; win.dataset.windowId=id; win.innerHTML=auxMarkup(id,title); document.body.appendChild(win);if(id==='listeler'||id==='staff'){win.style.width='900px';win.style.height='500px';}if(id==='shifts'){win.style.width='1120px';win.style.height='590px';}if(id==='setup'){win.style.width='840px';win.style.height='520px';}if(id.startsWith('member-')){win.style.width='900px';win.style.height='530px';}if(id.startsWith('employee-')){win.style.width='960px';win.style.height='620px';}
      const offset=document.querySelectorAll('.aux-window').length*24; Object.assign(win.style,{left:(230+offset)+'px',top:(70+offset)+'px'}); activateWindow(win);
      const task=document.createElement('button'); task.type='button'; task.className='task-item'; task.dataset.windowId=id; task.textContent=title; document.querySelector('.tray').before(task); task.addEventListener('click',()=>toggleTaskWindow(win,task));
      let move=null; const bar=win.querySelector('.aux-titlebar');
      bar.addEventListener('pointerdown',e=>{if(e.target.closest('button')||win.classList.contains('maximized'))return;const r=win.getBoundingClientRect();move={x:e.clientX,y:e.clientY,l:r.left,t:r.top};bar.setPointerCapture(e.pointerId);activateWindow(win);});
      bar.addEventListener('pointermove',e=>{if(!move)return;win.style.left=Math.max(0,Math.min(innerWidth-win.offsetWidth,move.l+e.clientX-move.x))+'px';win.style.top=Math.max(0,Math.min(innerHeight-31-win.offsetHeight,move.t+e.clientY-move.y))+'px';});
      bar.addEventListener('pointerup',()=>move=null); bar.addEventListener('dblclick',e=>{if(!e.target.closest('button'))win.classList.toggle('maximized');});
      win.addEventListener('pointerdown',()=>activateWindow(win));
      win.querySelector('[data-act="min"]').onclick=()=>minimizeDesktopWindow(win,task);
      win.querySelector('[data-act="max"]').onclick=e=>{win.classList.toggle('maximized');e.currentTarget.textContent=win.classList.contains('maximized')?'❐':'□';};
      const closeWindow=()=>{task.remove();win.remove();if(id.startsWith('member-'))delete memberCardPayloads[id];if(id.startsWith('employee-'))delete employeeCardPayloads[id];};
      win.querySelector('[data-act="close"]').onclick=closeWindow;
      if(id==='listeler'){const search=win.querySelector('[data-patient-search]'),status=win.querySelector('[data-patient-status]'),rows=[...win.querySelectorAll('.patient-table tbody tr')],count=win.querySelector('.list-count');const filterRows=()=>{const q=search.value.toLocaleLowerCase('tr-TR');let shown=0;rows.forEach(row=>{const visible=(!q||row.dataset.search.includes(q))&&(!status.value||row.dataset.status===status.value);row.hidden=!visible;if(visible)shown++;});count.textContent='Gösterilen '+shown;};search.addEventListener('input',filterRows);status.addEventListener('change',filterRows);rows.forEach(row=>row.addEventListener('click',()=>{rows.forEach(item=>item.classList.remove('selected'));row.classList.add('selected');openMemberCard(row);}));win.querySelector('[data-list-action="card"]').addEventListener('click',()=>{const selected=rows.find(row=>row.classList.contains('selected'));if(selected)openMemberCard(selected);else alert('Lütfen listeden bir üye seçin.');});win.querySelectorAll('[data-list-action="refresh"]').forEach(button=>button.addEventListener('click',()=>{search.value='';status.value='';filterRows();}));setupPatientColumns(win);}
      if(id==='setup')setupSetupWindow(win);
      if(id==='staff')setupEmployeeListWindow(win);
      if(id==='shifts')setupScheduleWindow(win);
      if(id.startsWith('employee-'))setupEmployeeCardWindow(win,id);
      if(id.startsWith('member-')){const form=win.querySelector('.member-card-form'),status=win.querySelector('.member-card-status');form.addEventListener('submit',async event=>{event.preventDefault();status.textContent='Kaydediliyor…';try{await saveMemberCard(id,form);status.textContent='Üye bilgileri kaydedildi.';}catch(error){status.textContent=error.message;}});win.querySelector('[data-member-action="close"]').onclick=closeWindow;}
    };
    const openMemberCard=async row=>{try{const payload=await apiFetch(endpoints.members+'/'+row.dataset.memberId),member={...payload.data,_row:row},id='member-'+member.id;memberCardPayloads[id]=member;openAuxWindow(id,'Üye Kartı • '+member.memberNo+' • '+member.name);}catch(error){alert(error.message);}};
    const displayDate=value=>{if(!value)return'';const parts=value.split('-');return parts.length===3?`${parts[2]}.${parts[1]}.${parts[0]}`:value;};
    const saveMemberCard=async(id,form)=>{const member=memberCardPayloads[id],values=Object.fromEntries(new FormData(form));values.durationMonths=values.durationMonths===''?null:Number(values.durationMonths);values.contractAmount=values.contractAmount===''?null:Number(values.contractAmount);const payload=await apiFetch(endpoints.members+'/'+member.id,{method:'PUT',body:JSON.stringify(values)}),saved=payload.data,row=member._row;Object.assign(member,saved);const summary=memberRecords.find(item=>item.id===saved.id);if(summary)Object.assign(summary,{memberNo:saved.memberNo,name:saved.name,phone:saved.phone,membershipType:saved.membershipType,validThrough:saved.validThrough,status:saved.status});if(!row)return;saved._row=row;row.dataset.search=`${saved.memberNo} ${saved.name} ${saved.phone||''}`.toLocaleLowerCase('tr-TR');row.dataset.status=saved.status;row.cells[0].textContent=saved.memberNo;row.cells[1].textContent=saved.name;row.cells[2].textContent=saved.phone||'';row.cells[3].textContent=saved.membershipType;row.cells[4].textContent=displayDate(saved.validThrough);row.cells[5].innerHTML=`<span class="status-badge ${saved.status==='aktif'?'active':'passive'}">${saved.status==='aktif'?'Aktif':'Pasif'}</span>`;};
    const menuScreens={
      'Üyeler':['listeler','Üyeler'],
      'Rezervasyon':['calendar','Rezervasyon'],
      'Ön Kasa':['cash','Ön Kasa'],
      'Stok':['stock','Stok'],
      'Personel':['staff','Personel'],
      'Çalışma Programı':['shifts','Çalışma Programı'],
      'Raporlar':['reports','Raporlar'],
      'Kurulum':['setup','Kurulum']
    };
    document.querySelectorAll('.start-menu a').forEach(link=>link.addEventListener('click',event=>{
      const screen=menuScreens[link.textContent.trim()]; if(!screen)return;
      event.preventDefault();
      if(screen[0]==='patient'){const task=document.getElementById('patient-task');appWindow.hidden=false;task.hidden=false;appWindow.classList.remove('minimized');requestAnimationFrame(centerWindow);activateWindow(appWindow);}
      else openAuxWindow(screen[0],screen[1]);
    }));
    let savedIconPositions={};try{savedIconPositions=JSON.parse(localStorage.getItem('hastaDesktopIconPositions')||'{}');}catch(e){}
    document.querySelectorAll('.desktop-icon').forEach(icon=>{const saved=savedIconPositions[icon.dataset.open];if(saved){icon.style.left=saved.left+'px';icon.style.top=saved.top+'px';}let iconDrag=null;icon.addEventListener('pointerdown',e=>{const r=icon.getBoundingClientRect();iconDrag={x:e.clientX,y:e.clientY,left:r.left,top:r.top,moved:false};icon.setPointerCapture(e.pointerId);});icon.addEventListener('pointermove',e=>{if(!iconDrag)return;const dx=e.clientX-iconDrag.x,dy=e.clientY-iconDrag.y;if(Math.abs(dx)+Math.abs(dy)>3){iconDrag.moved=true;icon.classList.add('dragging');}if(!iconDrag.moved)return;icon.style.left=Math.max(0,Math.min(innerWidth-icon.offsetWidth,iconDrag.left+dx))+'px';icon.style.top=Math.max(0,Math.min(innerHeight-31-icon.offsetHeight,iconDrag.top+dy))+'px';});icon.addEventListener('pointerup',()=>{if(iconDrag?.moved){icon.classList.remove('dragging');savedIconPositions[icon.dataset.open]={left:parseFloat(icon.style.left),top:parseFloat(icon.style.top)};localStorage.setItem('hastaDesktopIconPositions',JSON.stringify(savedIconPositions));}iconDrag=null;});icon.addEventListener('pointercancel',()=>{icon.classList.remove('dragging');iconDrag=null;});icon.addEventListener('click',()=>{document.querySelectorAll('.desktop-icon').forEach(i=>i.classList.remove('selected'));icon.classList.add('selected');});icon.addEventListener('dblclick',()=>{if(icon.dataset.open==='patient'){const task=document.getElementById('patient-task');appWindow.hidden=false;task.hidden=false;appWindow.classList.remove('minimized');requestAnimationFrame(centerWindow);activateWindow(appWindow);}else openAuxWindow(icon.dataset.open,icon.dataset.title);});});
    document.querySelectorAll('.desktop-icon').forEach(icon=>{let pressPoint=null,wasDragged=false;icon.addEventListener('pointerdown',e=>{pressPoint={x:e.clientX,y:e.clientY};wasDragged=false;icon.classList.remove('selected');},true);icon.addEventListener('pointermove',e=>{if(pressPoint&&Math.abs(e.clientX-pressPoint.x)+Math.abs(e.clientY-pressPoint.y)>3)wasDragged=true;},true);icon.addEventListener('click',e=>{if(wasDragged){e.preventDefault();e.stopImmediatePropagation();icon.classList.remove('selected');wasDragged=false;}pressPoint=null;},true);});
    document.querySelectorAll('select').forEach(select=>{if(select.parentElement.classList.contains('select-shell'))return;const shell=document.createElement('span');shell.className='select-shell';select.parentNode.insertBefore(shell,select);shell.appendChild(select);const arrow=document.createElement('span');arrow.className='select-arrow';arrow.textContent='▼';shell.appendChild(arrow);});
    const calendar=document.createElement('div');calendar.className='compact-calendar';calendar.hidden=true;document.body.appendChild(calendar);let calendarInput=null,calendarDate=new Date();
    const pad=n=>String(n).padStart(2,'0'),formatDate=d=>`${pad(d.getDate())}.${pad(d.getMonth()+1)}.${d.getFullYear()}`;
    const renderCalendar=()=>{const y=calendarDate.getFullYear(),m=calendarDate.getMonth(),first=(new Date(y,m,1).getDay()+6)%7,last=new Date(y,m+1,0).getDate(),months=['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'],week=['Pt','Sa','Ça','Pe','Cu','Ct','Pa'];let days=week.map(d=>`<span class="calendar-weekday">${d}</span>`).join('');for(let i=0;i<first;i++)days+='<span></span>';for(let d=1;d<=last;d++){const value=new Date(y,m,d),today=formatDate(value)===formatDate(new Date()),selected=calendarInput&&calendarInput.value===formatDate(value);days+=`<button type="button" class="calendar-day${today?' today':''}${selected?' selected':''}" data-day="${d}">${d}</button>`;}calendar.innerHTML=`<div class="calendar-head"><button type="button" data-nav="-1">‹</button><div class="calendar-title">${months[m]} ${y}</div><button type="button" data-nav="1">›</button></div><div class="calendar-grid">${days}</div><div class="calendar-foot"><button type="button" data-clear>Temizle</button><button type="button" data-today>Bugün</button></div>`;};
    const showCalendar=input=>{calendarInput=input;const parts=input.value.split('.');calendarDate=parts.length===3?new Date(Number(parts[2]),Number(parts[1])-1,Number(parts[0])):new Date();renderCalendar();calendar.hidden=false;const r=input.getBoundingClientRect();calendar.style.left=Math.max(4,Math.min(innerWidth-182,r.left))+'px';calendar.style.top=Math.max(4,Math.min(innerHeight-205,r.bottom+2))+'px';};
    document.querySelectorAll('[data-compact-date]').forEach(input=>{const shell=document.createElement('div');shell.className='date-field';input.parentNode.insertBefore(shell,input);shell.appendChild(input);const button=document.createElement('button');button.type='button';button.className='classic-tool-button';button.textContent='▦';button.setAttribute('aria-label','Takvimi aç');shell.appendChild(button);button.onclick=()=>showCalendar(input);input.addEventListener('focus',()=>showCalendar(input));});
    calendar.addEventListener('click',e=>{const nav=e.target.closest('[data-nav]'),day=e.target.closest('[data-day]');if(nav){calendarDate.setMonth(calendarDate.getMonth()+Number(nav.dataset.nav));renderCalendar();}if(day){calendarInput.value=formatDate(new Date(calendarDate.getFullYear(),calendarDate.getMonth(),Number(day.dataset.day)));calendar.hidden=true;}if(e.target.closest('[data-today]')){calendarInput.value=formatDate(new Date());calendar.hidden=true;}if(e.target.closest('[data-clear]')){calendarInput.value='';calendar.hidden=true;}});
    document.addEventListener('pointerdown',e=>{if(!calendar.hidden&&!e.target.closest('.compact-calendar,.date-field'))calendar.hidden=true;});
    document.addEventListener('keydown',e=>{if(e.key==='F2'){e.preventDefault();document.getElementById('patient-form').requestSubmit();}if(e.key==='F3'){e.preventDefault();document.getElementById('patient-form').reset();}});
  </script>
</body>
</html>
