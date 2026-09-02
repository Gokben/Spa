<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Spa</title>
  <style>
    :root { --brand:#107c41; --brand-dark:#0b5c30; --ink:#1f2937; --muted:#5f6b7a; --line:#d7dde6; --canvas:#f3f5f7; --surface:#fff; --focus:#0067c0; }
    * { box-sizing:border-box; } body { margin:0; background:var(--canvas); color:var(--ink); font:14px/1.4 "Segoe UI", system-ui, sans-serif; }
    .sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }
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
    .start-menu { position:fixed; z-index:19990; left:0; bottom:31px; width:298px; height:330px; padding:5px; display:grid; grid-template-columns:143px 1fr; grid-template-rows:20px 1fr; background:#3f4346; border:1px solid #1d262e; box-shadow:3px 3px 10px rgba(0,0,0,.45); transform:scaleY(.04); transform-origin:left bottom; opacity:0; visibility:hidden; transition:transform .34s cubic-bezier(.3,0,.2,1),opacity .22s ease,visibility 0s linear .34s; }
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
    .sms-setup-panel { height:calc(100% - 33px); padding:8px; overflow:auto; background:#fff9ed; }
    .sms-setup-panel .aux-toolbar { margin:0 0 7px; border:1px solid #c9ad67; }
    .sms-setup-grid { display:grid; grid-template-columns:minmax(320px,.8fr) minmax(420px,1.2fr); gap:9px; }
    .sms-card { padding:10px; border:1px solid #c9ad67; background:#fff; }
    .sms-card h3 { margin:0 0 9px; color:#165f4a; font:bold 13px Tahoma; }
    .sms-form { display:grid; grid-template-columns:145px minmax(180px,1fr); gap:7px 9px; align-items:center; }
    .sms-form label { font:bold 11px Tahoma; }
    .sms-form input,.sms-form textarea { width:100%; min-height:28px; padding:4px 7px; border:1px solid #b69445; background:#fffef9; font:12px Tahoma; }
    .sms-form textarea { min-height:88px; resize:vertical; }
    .sms-form .sms-check { display:flex; align-items:center; gap:6px; font-weight:normal; }
    .sms-form .sms-check input { width:15px; min-height:15px; }
    .sms-test-actions { display:flex; align-items:center; gap:8px; margin-top:9px; }
    .sms-note { margin:8px 0 0; color:#7b5b1b; font:10px Tahoma; }
    .sms-log { grid-column:1/-1; }
    .sms-log .shift-grid-wrap { height:auto; max-height:230px; }
    @media (max-width:900px) { .sms-setup-grid { grid-template-columns:1fr; } .sms-log { grid-column:auto; } }
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
    .member-card-tabs { display:grid; grid-template-columns:repeat(3,1fr); margin:-7px -7px 7px; border-bottom:1px solid #c9ad67; background:#0b473b; }
    .member-card-tabs button { position:relative; display:grid; place-items:center; align-content:center; gap:2px; min-height:64px; padding:7px 10px; color:#fff; border:0; border-right:1px solid rgba(255,255,255,.28); border-radius:0; background:linear-gradient(#178d7a,#0c6658); box-shadow:inset 0 1px rgba(255,255,255,.28); text-shadow:0 1px #063d34; font:bold 11px Tahoma; }
    .member-card-tabs button:last-child { border-right:0; }
    .member-card-tabs button:hover { background:linear-gradient(#25a38e,#107565); }
    .member-card-tabs button.active { background:linear-gradient(#d0ad5b,#9a7426); }
    .member-card-tabs button.active::after { content:""; position:absolute; left:50%; bottom:-9px; transform:translateX(-50%); border:9px solid transparent; border-top-color:#9a7426; border-bottom:0; }
    .member-card-tab-icon { font:bold 21px/1 Georgia,serif; }
    .member-card-panel[hidden] { display:none; }
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
    .stock-shell { height:calc(100% - 27px); overflow:auto; color:#2b261c; background:#fff9ed; }
    .stock-tabs { display:grid; grid-template-columns:repeat(4,1fr); border-bottom:1px solid #8f6918; background:#123c32; }
    .stock-tabs button { min-height:54px; padding:7px 8px; color:#fff; border:0; border-right:1px solid #597f72; border-radius:0; background:linear-gradient(#3d7061,#1d4d40); box-shadow:inset 0 1px rgba(255,255,255,.2); text-shadow:1px 1px #09261f; font:bold 11px Tahoma; }
    .stock-tabs button.active { color:#4c370e; background:linear-gradient(#f1d77d,#c4932d); text-shadow:1px 1px #fff1bd; }
    .stock-tabs button span { display:block; margin-bottom:2px; font-size:18px; }
    .stock-toolbar { display:flex; align-items:center; gap:4px; min-height:34px; padding:4px 6px; background:linear-gradient(#fffdf7,#ead7a4); border-bottom:1px solid #c9ad67; }
    .stock-toolbar button { height:25px; padding:3px 9px; color:#604510; font:10px Tahoma; }
    .stock-message { margin-left:auto; color:#245849; font:bold 10px Tahoma; }
    .stock-panel { padding:7px; }
    .stock-filters { display:flex; gap:7px; padding:6px; border:1px solid #c9ad67; background:#fffaf0; }
    .stock-filters input,.stock-filters select { height:26px; min-height:26px; font:10px Tahoma; }
    .stock-filters input { flex:1; }
    .stock-table-wrap { margin-top:6px; overflow:auto; border:1px solid #c9ad67; background:#fff; }
    .stock-table { width:100%; min-width:850px; border-collapse:collapse; font:10px Tahoma; }
    .stock-table th,.stock-table td { padding:6px 7px; border-right:1px solid #d9c991; border-bottom:1px solid #e2d5ad; text-align:left; white-space:nowrap; }
    .stock-table th { color:#fff; background:#245849; }
    .stock-table tbody tr:hover { background:#fff0bd; }
    .stock-table .critical { color:#a51d16; background:#ffe0d8; font-weight:bold; }
    .stock-row-actions { display:flex; gap:3px; }
    .stock-row-actions button { display:inline-flex; align-items:center; justify-content:center; width:25px; height:22px; padding:0; color:#245849; border-color:#9f7a29; background:linear-gradient(#fffef9,#e6d39e); }
    .stock-row-actions button[data-stock-action="history"] { color:#175b9a; }.stock-row-actions button[data-stock-action="delete"] { color:#a2382f; }
    .stock-row-actions svg { width:13px; height:13px; fill:none; stroke:currentColor; stroke-width:1.7; stroke-linecap:round; stroke-linejoin:round; pointer-events:none; }
    .stock-card-list-head { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:6px; padding:7px 9px; color:#fff; border:1px solid #1d4d40; background:linear-gradient(#3d7061,#245849); }
    .stock-card-list-head strong { font:bold 11px Tahoma; }.stock-card-list-head span { color:#f0d98f; font:9px Tahoma; }
    .stock-card-editor-toolbar { display:flex; align-items:center; margin-bottom:6px; padding:5px 6px; border:1px solid #c9ad67; background:linear-gradient(#fffdf7,#ead7a4); }
    .stock-card-editor-toolbar button { height:25px; padding:3px 9px; color:#604510; font:10px Tahoma; }
    .stock-form { display:grid; grid-template-columns:140px minmax(190px,1fr) 140px minmax(190px,1fr); gap:7px 9px; padding:10px; border:1px solid #c9ad67; background:#fffaf0; }
    .stock-form label { align-self:center; color:#4f4228; font:bold 10px Tahoma; }
    .stock-form input,.stock-form select,.stock-form textarea { width:100%; height:27px; min-height:27px; border-color:#b9a56c; border-radius:0; font:10px Tahoma; }
    .stock-category-picker { position:relative; min-width:0; }
    .stock-category-trigger { display:flex; align-items:center; justify-content:space-between; gap:10px; width:100%; min-height:34px; padding:6px 10px; border:1px solid #b9a56c; background:#fff; color:#1f2b2a; text-align:left; font:11px Tahoma; cursor:pointer; }
    .stock-category-trigger:hover,.stock-category-trigger[aria-expanded="true"] { border-color:#9c7b2d; box-shadow:0 0 0 1px #e4c66d inset; }
    .stock-category-trigger-arrow { color:#6a5523; font-size:13px; }
    .stock-category-menu { position:absolute; z-index:40; top:calc(100% + 2px); left:0; right:0; display:flex; flex-direction:column; gap:2px; max-height:190px; padding:5px; overflow:auto; border:1px solid #9c7b2d; background:#fffdf7; box-shadow:0 5px 12px rgba(30,38,32,.24); }
    .stock-category-menu[hidden] { display:none; }
    .stock-form .stock-category-menu > label { align-self:stretch; justify-self:stretch; display:flex; align-items:center; justify-content:flex-start; gap:7px; width:auto; min-height:28px; margin:0; padding:4px 7px; box-sizing:border-box; border:1px solid transparent; background:#f8edcf; text-align:left; cursor:pointer; font:10px Tahoma; }
    .stock-category-menu label:hover { border-color:#c9ad67; background:#fff0bd; }
    .stock-category-menu input { width:auto; height:auto; min-height:0; }
    .stock-category-empty { padding:6px; color:#76694e; font-style:italic; }
    .stock-form textarea { grid-column:span 3; height:58px; resize:vertical; }
    .stock-empty { padding:25px !important; color:#76694e; text-align:center !important; }
    .aux-window[data-window-id="stock"] { min-width:760px; min-height:460px; }
    @media(max-width:780px){.stock-tabs{grid-template-columns:repeat(2,1fr)}.stock-form{grid-template-columns:115px 1fr}.stock-form textarea{grid-column:auto}.stock-filters{flex-direction:column}}
    .cash-shell { height:calc(100% - 27px); overflow:auto; color:#2b261c; background:#fff9ed; }
    .cash-tabs { display:grid; grid-template-columns:repeat(4,1fr); border-bottom:1px solid #8f6918; background:#123c32; }
    .cash-tabs button { min-height:54px; padding:7px 8px; color:#fff; border:0; border-right:1px solid #597f72; border-radius:0; background:linear-gradient(#3d7061,#1d4d40); box-shadow:inset 0 1px rgba(255,255,255,.2); text-shadow:1px 1px #09261f; font:bold 11px Tahoma; }
    .cash-tabs button.active { color:#4c370e; background:linear-gradient(#f1d77d,#c4932d); text-shadow:1px 1px #fff1bd; }
    .cash-tabs button span { display:block; margin-bottom:2px; font-size:18px; }
    .cash-toolbar { display:flex; align-items:center; gap:4px; min-height:34px; padding:4px 6px; background:linear-gradient(#fffdf7,#ead7a4); border-bottom:1px solid #c9ad67; }
    .cash-toolbar button { height:25px; padding:3px 9px; color:#604510; font:10px Tahoma; }
    .cash-message { margin-left:auto; color:#245849; font:bold 10px Tahoma; }
    .cash-panel { padding:7px; }
    .cash-summary { display:grid; grid-template-columns:repeat(4,1fr); gap:6px; margin-bottom:7px; }
    .cash-summary article { padding:8px 10px; border:1px solid #c9ad67; background:#fffaf0; }
    .cash-summary span { display:block; color:#76694e; font:9px Tahoma; }
    .cash-summary strong { display:block; margin-top:3px; color:#245849; font:bold 15px Tahoma; }
    .cash-summary .expense strong,.cash-summary .negative { color:#a51d16; }
    .cash-filters { display:flex; align-items:end; gap:7px; padding:6px; border:1px solid #c9ad67; background:#fffaf0; }
    .cash-filters label { display:grid; gap:2px; font:9px Tahoma; }
    .cash-filters input,.cash-filters select { height:26px; min-height:26px; font:10px Tahoma; }
    .cash-filters input[type=search] { flex:1; }
    .cash-table-wrap { margin-top:6px; overflow:auto; border:1px solid #c9ad67; background:#fff; }
    .cash-table { width:100%; min-width:820px; border-collapse:collapse; font:10px Tahoma; }
    .cash-table th,.cash-table td { padding:6px 7px; border-right:1px solid #d9c991; border-bottom:1px solid #e2d5ad; text-align:left; white-space:nowrap; }
    .cash-table th { color:#fff; background:#245849; }
    .cash-table .income { color:#147039; font-weight:bold; }.cash-table .expense { color:#a51d16; font-weight:bold; }
    .cash-form { display:grid; grid-template-columns:140px minmax(190px,1fr) 140px minmax(190px,1fr); gap:7px 9px; padding:10px; border:1px solid #c9ad67; background:#fffaf0; }
    .cash-form label { align-self:center; color:#4f4228; font:bold 10px Tahoma; }
    .cash-form input,.cash-form select,.cash-form textarea { width:100%; height:27px; min-height:27px; border-color:#b9a56c; border-radius:0; font:10px Tahoma; }
    .cash-form textarea { grid-column:span 3; height:52px; resize:vertical; }
    .cash-closing-summary { margin-top:7px; padding:9px; border:1px solid #c9ad67; background:#fffaf0; font:10px Tahoma; }
    .cash-row-actions { display:flex; gap:3px; }.cash-row-actions button { width:23px; height:21px; padding:0; font-size:11px; }
    .aux-window[data-window-id="cash"] { min-width:760px; min-height:460px; }
    @media(max-width:780px){.cash-tabs{grid-template-columns:repeat(2,1fr)}.cash-summary{grid-template-columns:repeat(2,1fr)}.cash-form{grid-template-columns:115px 1fr}.cash-form textarea{grid-column:auto}.cash-filters{align-items:stretch;flex-direction:column}}
    .reservation-shell { height:calc(100% - 27px); overflow:auto; color:#2b261c; background:#fff9ed; }
    .reservation-tabs { display:grid; grid-template-columns:repeat(3,1fr); border-bottom:1px solid #8f6918; background:#123c32; }
    .reservation-tabs button { min-height:54px; padding:7px 8px; color:#fff; border:0; border-right:1px solid #597f72; border-radius:0; background:linear-gradient(#3d7061,#1d4d40); box-shadow:inset 0 1px rgba(255,255,255,.2); text-shadow:1px 1px #09261f; font:bold 11px Tahoma; }
    .reservation-tabs button.active { color:#4c370e; background:linear-gradient(#f1d77d,#c4932d); text-shadow:1px 1px #fff1bd; }
    .reservation-tabs button span { display:block; margin-bottom:2px; font-size:18px; }
    .reservation-toolbar { display:flex; align-items:center; gap:4px; min-height:34px; padding:4px 6px; background:linear-gradient(#fffdf7,#ead7a4); border-bottom:1px solid #c9ad67; }
    .reservation-toolbar button { height:25px; padding:3px 9px; color:#604510; font:10px Tahoma; }.reservation-message{margin-left:auto;color:#245849;font:bold 10px Tahoma}
    .reservation-toolbar [data-reservation-command="new"] { display:none; }
    .reservation-view-switch { display:flex; margin-left:8px; border:1px solid #9f7a29; }
    .reservation-view-switch button { min-width:56px; border:0; border-right:1px solid #b7964f; border-radius:0; background:linear-gradient(#fffef9,#e6d39e); box-shadow:none; }
    .reservation-view-switch button:last-child { border-right:0; }
    .reservation-view-switch button.active { color:#fff; background:linear-gradient(#3d7061,#1d4d40); text-shadow:1px 1px #09261f; }
    .reservation-panel { padding:7px; }
    .reservation-month-head { display:flex; align-items:center; justify-content:center; gap:10px; padding:6px; border:1px solid #c9ad67; background:#fffaf0; }
    .reservation-month-head strong { min-width:160px; color:#604510; text-align:center; font:bold 13px Tahoma; }.reservation-month-head button{width:28px;height:24px;padding:0}
    .reservation-month-head [data-reservation-today] { width:auto; min-width:58px; padding:0 10px; white-space:nowrap; }
    .reservation-calendar { display:grid; grid-template-columns:repeat(7,minmax(115px,1fr)); margin-top:6px; border-left:1px solid #c9ad67; border-top:1px solid #c9ad67; background:#fff; }
    .reservation-weekday { padding:6px; color:#fff; background:#245849; border-right:1px solid #6f9589; text-align:center; font:bold 9px Tahoma; }
    .reservation-day { min-height:90px; padding:4px; border-right:1px solid #d9c991; border-bottom:1px solid #d9c991; background:#fffdf8; }.reservation-day.muted{background:#f0eadc}.reservation-day.today{box-shadow:inset 0 0 0 2px #c4932d}
    .reservation-day-head { display:flex; justify-content:space-between; color:#604510; font:bold 10px Tahoma; }.reservation-day-head button{width:18px;height:17px;padding:0;border:0;background:transparent;box-shadow:none;color:#ad7e1f}
    .reservation-event { display:block; margin-top:3px; padding:3px 4px; overflow:hidden; color:#fff; background:#2e7460; border-left:3px solid #d8b95d; white-space:nowrap; text-overflow:ellipsis; font:9px Tahoma; cursor:pointer; }.reservation-event.confirmed{background:#176a8b}.reservation-event.completed{background:#6e7c49}.reservation-event.cancelled{background:#9a8f79;text-decoration:line-through}.reservation-event.no_show{background:#a5483f}.reservation-event:hover,.reservation-event.confirmed:hover,.reservation-event.completed:hover,.reservation-event.cancelled:hover,.reservation-event.no_show:hover{color:#fff;background:#176fa6}
    .reservation-week-calendar { display:grid; grid-template-columns:repeat(7,minmax(130px,1fr)); margin-top:6px; border-left:1px solid #c9ad67; border-top:1px solid #c9ad67; background:#fff; }
    .reservation-week-column { min-height:330px; padding:5px; border-right:1px solid #d9c991; border-bottom:1px solid #d9c991; background:#fffdf8; }.reservation-week-column.today{box-shadow:inset 0 0 0 2px #c4932d}
    .reservation-week-column .reservation-weekday { margin:-5px -5px 6px; }.reservation-week-column .reservation-day-head{margin-bottom:5px}
    .reservation-day-view { margin-top:6px; border:1px solid #c9ad67; background:#fff; }
    .reservation-day-title { display:flex; align-items:center; justify-content:space-between; padding:8px 10px; color:#fff; background:#245849; font:bold 11px Tahoma; }
    .reservation-day-title button { width:24px; height:22px; padding:0; }
    .reservation-timeline { height:742px; overflow:auto; background:#fffdf8; }
    .reservation-therapist-grid { --therapist-count:1; display:grid; grid-template-columns:62px repeat(var(--therapist-count),minmax(190px,1fr)); grid-template-rows:42px 840px; min-width:calc(62px + (var(--therapist-count) * 190px)); }
    .reservation-therapist-corner { position:sticky; top:0; left:0; z-index:5; border-right:1px solid #c9ad67; border-bottom:1px solid #c9ad67; background:#f6ead0; }
    .reservation-therapist-head { position:sticky; top:0; z-index:4; display:grid; place-items:center; padding:5px 8px; color:#fff; border-right:1px solid #87a99f; border-bottom:1px solid #c9ad67; background:linear-gradient(#4a806f,#245849); text-align:center; text-transform:uppercase; font:bold 10px Tahoma; }
    .reservation-therapist-head small { display:block; margin-top:2px; color:#f0d98f; text-transform:none; font:9px Tahoma; }
    .reservation-no-therapist { display:grid; min-height:220px; place-items:center; padding:20px; color:#765f27; background:#fffaf0; text-align:center; font:bold 11px Tahoma; }
    .reservation-time-axis,.reservation-time-canvas { position:relative; height:100%; }
    .reservation-time-axis { position:sticky; left:0; z-index:3; color:#175b9a; border-right:1px solid #c9ad67; background:repeating-linear-gradient(to bottom,#f8edd7 0,#f8edd7 60px,#ecdfc2 60px,#ecdfc2 120px); font:bold 11px Tahoma; }
    .reservation-time-label { position:absolute; right:7px; transform:translateY(-50%); }
    .reservation-time-label:first-child { top:7px !important; transform:none; font-weight:bold; }.reservation-time-label:last-child { top:calc(100% - 7px) !important; transform:translateY(-100%); font-weight:bold; }
    .reservation-time-canvas { min-width:190px; border-right:1px solid #9a947f; background:repeating-linear-gradient(to bottom,#fffdf8 0,#fffdf8 60px,#edf4ef 60px,#edf4ef 120px); }
    .reservation-time-line { position:absolute; right:0; left:0; border-top:1px solid #cdbb8d; }.reservation-time-line.half{border-top:1px dotted rgba(157,145,111,.42)}
    .reservation-day-event { position:absolute; z-index:2; min-height:24px; padding:5px 7px; overflow:hidden; color:#fff; border:1px solid #174e40; border-left:5px solid #d8b95d; border-radius:2px; background:#2e7460; box-shadow:0 1px 2px rgba(0,0,0,.2); text-align:left; font:9px Tahoma; cursor:grab; }.reservation-day-event:hover,.reservation-day-event.confirmed:hover,.reservation-day-event.completed:hover,.reservation-day-event.cancelled:hover,.reservation-day-event.no_show:hover{color:#fff;border-color:#0d527f;background:#176fa6;box-shadow:0 2px 5px rgba(0,0,0,.34)}.reservation-day-event:active{cursor:grabbing}.reservation-day-event.dragging{opacity:.4}.reservation-therapist-canvas.drag-over{box-shadow:inset 0 0 0 3px #d4ae42;background-color:#fff3c9}.reservation-day-event.confirmed{background:#176a8b}.reservation-day-event.completed{background:#6e7c49}.reservation-day-event.cancelled{background:#9a8f79;text-decoration:line-through}.reservation-day-event.no_show{background:#a5483f}.reservation-day-event strong,.reservation-day-event span{display:block;overflow:hidden;color:inherit;white-space:nowrap;text-overflow:ellipsis}.reservation-day-event strong{margin-bottom:2px;color:inherit}
    .reservation-hover-card { position:fixed; z-index:40050; width:285px; padding:7px; pointer-events:none; color:#f7fbff; border:1px solid #7796ac; border-radius:3px; background:rgba(24,38,49,.97); box-shadow:3px 4px 12px rgba(0,0,0,.42); font:10px/1.35 Tahoma; }
    .reservation-hover-title { margin:-7px -7px 6px; padding:7px 8px; color:#fff; border-bottom:1px solid #7696ad; background:#176fa6; font:bold 11px Tahoma; }
    .reservation-hover-row { display:grid; grid-template-columns:82px 1fr; gap:7px; padding:3px 2px; border-bottom:1px dotted rgba(255,255,255,.2); }
    .reservation-hover-row:last-child { border-bottom:0; }.reservation-hover-row span{color:#c9dceb}.reservation-hover-row strong{overflow-wrap:anywhere;color:#fff;font-weight:normal;white-space:pre-wrap}
    .reservation-empty { position:absolute; top:45%; right:0; left:0; color:#76694e; text-align:center; font:11px Tahoma; }
    .reservation-context-menu { position:fixed; z-index:30010; width:145px; padding:3px; color:#111; border:1px solid #71869c; background:#f2f2f2; box-shadow:2px 3px 8px rgba(0,0,0,.38); font:10px Tahoma; }
    .reservation-context-menu button { display:block; width:100%; height:27px; padding:4px 9px; border:0; border-radius:0; background:transparent; box-shadow:none; text-align:left; font:10px Tahoma; }.reservation-context-menu button:hover:not(:disabled){color:#fff;background:#316ac5}.reservation-context-menu button:disabled{color:#999}.reservation-context-menu hr{margin:3px;border:0;border-top:1px solid #bbb}
    .reservation-entry-screen { position:absolute; inset:0; z-index:30; display:flex; flex-direction:column; overflow:hidden; color:#263a32; background:#f4ead2; }
    .reservation-entry-titlebar { display:flex; align-items:center; height:31px; min-height:31px; padding:0 5px 0 12px; color:#fff; border-bottom:1px solid #c9ad67; background:linear-gradient(#245849,#123c32); font:bold 11px Tahoma; }
    .reservation-entry-titlebar .window-controls { margin-left:auto; }.reservation-entry-close{display:grid;place-items:center;width:25px;height:23px;padding:0;color:#fff;border:1px solid #ead7a4;border-radius:2px;background:linear-gradient(#d7b651,#9c711b);box-shadow:inset 1px 1px rgba(255,255,255,.55);font:bold 15px Tahoma}.reservation-entry-close:hover{background:linear-gradient(#dc6b5e,#a42f28)}
    .reservation-entry-toolbar { display:flex; align-items:center; gap:7px; min-height:43px; padding:5px 10px; border-bottom:1px solid #c9ad67; background:linear-gradient(#fffdf7,#ead7a4); }.reservation-entry-toolbar button{height:29px;padding:4px 13px;color:#173e33;border:1px solid #ad7e1f;border-radius:3px;background:linear-gradient(#fffdf7,#e3c66e);box-shadow:inset 1px 1px #fff;font:bold 10px Tahoma}.reservation-entry-toolbar button:hover{background:linear-gradient(#fff8d5,#d8b95d)}.reservation-entry-message{margin-left:auto;color:#765611;font:bold 10px Tahoma}
    .reservation-entry-body { flex:1; min-height:0; overflow:auto; padding:8px; }
    .reservation-form { display:block; min-width:760px; min-height:100%; padding:8px; border:1px solid #c9ad67; background:#f4ead2; font:10px Tahoma; }
    .reservation-entry-tabs { display:flex; gap:2px; margin-bottom:7px; border-bottom:1px solid #ad7e1f; }.reservation-entry-tabs button{height:28px;padding:4px 16px;color:#fff;border:1px solid #1d4d40;border-bottom:0;border-radius:3px 3px 0 0;background:linear-gradient(#3d7061,#245849);box-shadow:none;font:bold 10px Tahoma}.reservation-entry-tabs button.active{color:#5f4611;border-color:#ad7e1f;background:linear-gradient(#fffdf7,#e3c66e)}
    .reservation-entry-panel { display:grid; gap:7px; }.reservation-entry-summary{display:grid;grid-template-columns:repeat(4,minmax(140px,1fr));gap:7px;padding:8px;border:1px solid #c9ad67;background:#fffaf0}.reservation-form-field label{display:block;margin-bottom:3px;color:#173e33;font:bold 10px Tahoma}.reservation-form input,.reservation-form select,.reservation-form textarea{width:100%;height:28px;min-height:28px;border:1px solid #c9ad67;border-radius:2px;background:#fffdf7;font:10px Tahoma}.reservation-form input:focus,.reservation-form select:focus,.reservation-form textarea:focus{outline:1px solid #245849;border-color:#245849}.reservation-form textarea{height:88px;resize:vertical}
    .reservation-entry-grid { display:grid; grid-template-columns:minmax(430px,1.35fr) minmax(280px,.85fr); gap:7px; }.reservation-entry-box{min-width:0;padding:7px;border:1px solid #c9ad67;border-radius:4px;background:#fffaf0;box-shadow:inset 0 1px #fff}.reservation-entry-title{margin:-2px 0 7px;padding:5px 7px;color:#fff;background:linear-gradient(90deg,#245849,#3d7061);font:bold 11px Tahoma}.reservation-picker-toolbar{display:grid;grid-template-columns:1fr 150px;gap:6px;margin-bottom:6px}.reservation-picker-toolbar input,.reservation-picker-toolbar select{height:27px}
    .reservation-template-table,.reservation-guest-table,.reservation-extra-table{width:100%;border-collapse:collapse;background:#fffdf7;font:10px Tahoma}.reservation-template-table th,.reservation-template-table td,.reservation-guest-table th,.reservation-guest-table td,.reservation-extra-table th,.reservation-extra-table td{padding:5px 6px;border-right:1px solid #d9c991;border-bottom:1px solid #e2d5ad;text-align:left}.reservation-template-table th,.reservation-guest-table th,.reservation-extra-table th{color:#fff;background:#245849}.reservation-template-table tbody tr,.reservation-guest-table tbody tr{cursor:pointer}.reservation-template-table tbody tr:hover,.reservation-guest-table tbody tr:hover{background:#f7e3a4}.reservation-template-table tbody tr.selected,.reservation-guest-table tbody tr.selected{color:#173e33;background:#fff0bd}.reservation-table-scroll{height:210px;overflow:auto;border:1px solid #c9ad67}
    .reservation-selected-service{display:grid;gap:7px;min-height:130px;padding:10px;border:1px solid #c9ad67;border-radius:3px;background:#fffdf7}.reservation-selected-service strong{color:#245849;font-size:12px}.reservation-selected-service span{color:#6b6047}.reservation-extra-tools{display:flex;gap:5px;margin:7px 0}.reservation-extra-tools button{width:30px;height:27px;padding:0;color:#173e33;border-color:#ad7e1f;background:linear-gradient(#fffdf7,#e3c66e);font-size:17px}.reservation-extra-empty{height:100px;color:#796d50;text-align:center}
    .reservation-guest-section{display:grid;grid-template-columns:minmax(520px,1fr) 260px;gap:7px}.reservation-guest-search{margin-bottom:6px}.reservation-selected-guest{min-height:180px;padding:12px;border:1px solid #c9ad67;border-radius:3px;background:#fffdf7}.reservation-selected-guest strong{display:block;margin-bottom:8px;color:#245849;font-size:12px}.reservation-selected-guest span{display:block;margin-top:5px;color:#6b6047}.reservation-detail-grid{display:grid;grid-template-columns:150px 1fr 150px 1fr;gap:8px;padding:10px;border:1px solid #c9ad67;background:#fffaf0}.reservation-detail-grid label{align-self:center;color:#173e33;font-weight:bold}.reservation-detail-grid textarea{grid-column:2/5;height:130px}
    .reservation-filters{display:flex;gap:7px;padding:6px;border:1px solid #c9ad67;background:#fffaf0}.reservation-filters input{flex:1;height:26px;min-height:26px;font:10px Tahoma}.reservation-filters select{width:160px;height:26px;min-height:26px;font:10px Tahoma}
    .reservation-table-wrap{margin-top:6px;overflow:auto;border:1px solid #c9ad67;background:#fff}.reservation-table{width:100%;min-width:880px;border-collapse:collapse;font:10px Tahoma}.reservation-table th,.reservation-table td{padding:6px 7px;border-right:1px solid #d9c991;border-bottom:1px solid #e2d5ad;text-align:left;white-space:nowrap}.reservation-table th{color:#fff;background:#245849}.reservation-actions{display:flex;gap:3px}.reservation-actions button{width:23px;height:21px;padding:0;font-size:11px}
    .aux-window[data-window-id="calendar"]{min-width:800px;min-height:500px}@media(max-width:800px){.reservation-calendar,.reservation-week-calendar{min-width:805px}.reservation-form{grid-template-columns:115px 1fr}.reservation-form textarea{grid-column:auto}.reservation-filters{flex-direction:column}.reservation-filters select{width:100%}}
    .packages-shell { height:calc(100% - 27px); overflow:auto; color:#2b261c; background:#fff9ed; }
    .packages-toolbar { display:flex; align-items:center; gap:4px; min-height:34px; padding:4px 6px; background:linear-gradient(#fffdf7,#ead7a4); border-bottom:1px solid #c9ad67; }
    .packages-toolbar button { height:25px; padding:3px 9px; color:#604510; font:10px Tahoma; }
    .packages-message { margin-left:auto; color:#245849; font:bold 10px Tahoma; }
    .packages-body { padding:12px; }
    .packages-intro { margin:0 0 10px; color:#604510; font:11px/1.5 Tahoma; }
    .packages-editor { margin-bottom:10px; padding:10px; border:1px solid #c9ad67; background:#fffaf0; }
    .packages-editor[hidden] { display:none; }
    .packages-form { display:grid; grid-template-columns:145px minmax(220px,1fr); gap:7px 9px; align-items:start; }
    .packages-form label { padding-top:7px; color:#4f4228; font:bold 10px Tahoma; }
    .packages-form input,.packages-form textarea { width:100%; min-height:29px; padding:5px 7px; border:1px solid #b9a56c; border-radius:0; background:#fff; font:10px Tahoma; }
    .packages-form textarea { height:56px; resize:vertical; }
    .packages-form-actions { grid-column:2; display:flex; gap:5px; }
    .packages-form-actions button { height:27px; padding:4px 10px; color:#604510; font:10px Tahoma; }
    .packages-table-wrap { overflow:auto; border:1px solid #c9ad67; background:#fffdf8; }
    .packages-table { width:100%; min-width:760px; border-collapse:collapse; table-layout:fixed; font:12px/1.55 Tahoma; }
    .packages-table th,.packages-table td { padding:12px 14px; border-right:1px solid #d9c991; border-bottom:1px solid #e2d5ad; text-align:left; vertical-align:top; }
    .packages-table th:last-child,.packages-table td:last-child { border-right:0; }
    .packages-table th { color:#fff9ed; background:linear-gradient(#3d7061,#1d4d40); font:bold 12px Tahoma; }
    .packages-table tbody tr:nth-child(even) { background:#f8edcf; }
    .packages-table tbody tr:hover { background:#fff0bd; }
    .packages-table td:first-child { color:#123c32; font-weight:bold; }
    .packages-table th:nth-child(1) { width:21%; }.packages-table th:nth-child(2) { width:14%; }.packages-table th:nth-child(3) { width:31%; }.packages-table th:nth-child(4) { width:25%; }.packages-table th:nth-child(5) { width:9%; }
    .package-row-actions { display:flex; gap:4px; }
    .package-row-actions button { display:inline-flex; flex:0 0 28px; align-items:center; justify-content:center; width:28px; height:24px; padding:0; color:#245849; border:1px solid #9f7a29; background:linear-gradient(#fffef9,#e6d39e); box-shadow:inset 0 1px #fff; }
    .package-row-actions button:hover { color:#604510; border-color:#765817; background:#f5df99; }
    .package-row-actions button[data-package-action="delete"] { color:#a2382f; }
    .package-row-actions svg { width:13px; height:13px; fill:none; stroke:currentColor; stroke-width:1.7; stroke-linecap:round; stroke-linejoin:round; pointer-events:none; }
    .aux-window[data-window-id="packages"] { min-width:760px; min-height:420px; }
    @media(max-width:780px){.packages-form{grid-template-columns:110px minmax(190px,1fr)}}
    .current-shell { height:calc(100% - 27px); overflow:auto; color:#173e33; background:#fff9ed; font:10px Tahoma; }
    .current-list-toolbar,.current-card-toolbar { display:flex; align-items:center; gap:6px; min-height:38px; padding:5px 8px; border-bottom:1px solid #c9ad67; background:linear-gradient(#fffdf7,#ead7a4); }
    .current-list-toolbar .current-new { margin-left:auto; color:#fff; border-color:#087e3c; background:linear-gradient(#20b660,#078d42); text-shadow:0 1px #075c30; font-weight:bold; }
    .current-list-toolbar button,.current-card-toolbar button { height:27px; padding:4px 11px; font:10px Tahoma; }
    .current-message { margin-left:auto; color:#245849; font-weight:bold; }
    .current-body { padding:8px; }
    .current-search { width:100%; height:28px; margin-bottom:7px; padding:4px 7px; border:1px solid #c9ad67; background:#fff; font:10px Tahoma; }
    .current-table-wrap { overflow:auto; border:1px solid #c9ad67; background:#fff; }
    .current-table { width:100%; min-width:860px; border-collapse:collapse; font:10px Tahoma; }
    .current-table th,.current-table td { padding:6px 7px; border-right:1px solid #d8d2c3; border-bottom:1px solid #ded8c9; text-align:left; white-space:nowrap; }
    .current-table th { color:#fff; background:linear-gradient(#3d7061,#1d4d40); font-weight:bold; }
    .current-table tbody tr:nth-child(even) { background:#f5f7f4; }
    .current-table tbody tr[data-current-id] { cursor:pointer; }
    .current-table tbody tr[data-current-id]:hover { background:#fff0bd; }
    .current-actions { display:flex; gap:4px; }
    .current-actions button { display:inline-flex; align-items:center; justify-content:center; width:29px; height:24px; padding:0; color:#fff; border:1px solid rgba(0,0,0,.16); border-radius:1px; box-shadow:inset 0 1px rgba(255,255,255,.45); }
    .current-actions button[data-current-action="movements"] { background:linear-gradient(#ffbc4a,#ee941b); }
    .current-actions button[data-current-action="edit"] { background:linear-gradient(#21bd62,#078c40); }
    .current-actions button[data-current-action="delete"] { background:linear-gradient(#ff6464,#dc3030); }
    .current-actions svg { width:14px; height:14px; fill:none; stroke:currentColor; stroke-width:1.8; stroke-linecap:round; stroke-linejoin:round; pointer-events:none; }
    .current-card-heading { display:flex; align-items:center; min-height:34px; padding:5px 10px; color:#fff; border:1px solid #1d4d40; background:linear-gradient(#3d7061,#1d4d40); font:bold 12px Tahoma; }
    .current-card-heading span { margin-left:8px; color:#f3cd62; font-size:17px; }
    .current-fieldset { margin:7px; padding:7px; border:1px solid #c9ad67; background:#fffaf0; }
    .current-fieldset legend { padding:4px 8px; color:#fff; border:1px solid #245849; background:linear-gradient(#3d7061,#245849); font:bold 11px Tahoma; }
    .current-form-grid { display:grid; grid-template-columns:repeat(3,minmax(180px,1fr)); gap:7px 10px; }
    .current-form-field label { display:block; margin-bottom:3px; color:#173e33; font-weight:bold; }
    .current-form-field input,.current-form-field select,.current-form-field textarea { width:100%; min-height:29px; padding:4px 7px; border:1px solid #c9ad67; border-radius:0; background:#fff; font:10px Tahoma; }
    .current-form-field textarea { height:63px; resize:vertical; }
    .current-card-footer { display:flex; justify-content:flex-end; gap:7px; padding:7px; border-top:1px solid #c9ad67; background:linear-gradient(#fffdf7,#ead7a4); }
    .current-card-footer button { height:29px; padding:4px 13px; color:#fff; border-color:#087e3c; background:linear-gradient(#20b660,#078d42); font:bold 10px Tahoma; }
    .current-ledger-title { padding:9px 10px; color:#173e33; border:1px solid #c9ad67; background:#e6f1ed; font:bold 11px Tahoma; }
    .current-ledger-table { min-width:1120px; }
    .current-ledger-table td { height:34px; }
    .current-invoice-toggle { width:27px; height:27px; padding:0; color:#173e33; border:1px solid #d68b19; background:linear-gradient(#ffc45c,#f39a20); font:bold 17px Tahoma; }
    .current-invoice-detail td { padding:0 7px 7px; background:#f7fbfa; }
    .current-item-table { width:100%; border-collapse:collapse; color:#173e33; background:#fff; }
    .current-item-table th,.current-item-table td { padding:7px; border:1px solid #cdd9d5; }
    .current-item-table th { color:#173e33; background:linear-gradient(#f5faf8,#d8e7e2); }
    .current-empty { padding:32px !important; color:#7b6b47; text-align:center !important; }
    .aux-window[data-window-id="current-accounts"] { min-width:820px; min-height:480px; }
    @media(max-width:900px){.current-form-grid{grid-template-columns:1fr 1fr}.current-form-field.current-wide{grid-column:1/-1}}
    .aux-window.maximized { top:0 !important; left:0 !important; width:100vw !important; height:calc(100vh - 31px) !important; transition:left .34s ease,width .34s ease; }
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
    .app.maximized { top:0 !important; left:0 !important; right:auto !important; width:100vw !important; height:calc(100vh - 31px) !important; border-radius:0; transition:left .34s ease,width .34s ease; }
    body.start-menu-open .app.maximized,body.start-menu-open .aux-window.maximized { left:212px !important; width:calc(100vw - 212px) !important; }
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
  <div class="start-menu" id="start-menu"><h3>Menü</h3><div class="start-left"><a href="#">Üyeler</a><a href="#">Rezervasyon</a><a href="#">Ön Kasa</a><a href="#">Stok</a><a href="#">Personel</a><a href="#">Raporlar</a><a href="#">Kurulum</a><a href="#">Paketler</a><a href="#">Cari Kartlar</a></div><div class="start-right"><a href="#" id="wallpaper-menu-button">Görünüm Ayarları</a><a href="#" id="logout-button">Çıkış</a></div><div class="wallpaper-picker" id="wallpaper-picker"><div class="picker-label">Duvar Kâğıdı</div><button type="button" data-wallpaper="wallpaper-blue.png" style="background-image:url('wallpaper-blue.png')"><span>Mavi</span></button><button type="button" data-wallpaper="wallpaper-green.png" style="background-image:url('wallpaper-green.png')"><span>Yeşil</span></button><button type="button" data-wallpaper="wallpaper-purple.png" style="background-image:url('wallpaper-purple.png')"><span>Mor</span></button><div class="picker-label">Ekran Rengi</div><button type="button" class="theme-swatch" data-theme="gray"><span>Gri</span></button><button type="button" class="theme-swatch" data-theme="green"><span>Yeşil</span></button><button type="button" class="theme-swatch" data-theme="blue"><span>Mavi</span></button></div></div>
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
          <div class="field"><label for="telefon1">Telefon 1</label><input id="telefon1" name="telefon1" type="tel" inputmode="numeric" autocomplete="tel" maxlength="17" data-phone-input placeholder="0 (XXX) XXX XX XX"></div>
          <div class="field"><label for="yakinlik_derecesi">Yakınlık Derecesi</label><input id="yakinlik_derecesi" name="yakinlik_derecesi" placeholder="Kendisi, eşi, yakını..."></div>
          <div class="field"><label for="telefon2">Telefon 2</label><input id="telefon2" name="telefon2" type="tel" inputmode="numeric" maxlength="17" data-phone-input placeholder="0 (XXX) XXX XX XX"></div>
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
    const endpoints={login:@json(route('login')),logout:@json(url('/logout')),members:@json(url('/api/members')),workShifts:@json(url('/api/work-shifts')),businessHours:@json(url('/api/business-hours')),occupations:@json(url('/api/occupations')),workGroups:@json(url('/api/work-groups')),categories:@json(url('/api/categories')),employees:@json(url('/api/employees')),employeeSchedules:@json(url('/api/employee-schedules')),stockItems:@json(url('/api/stock-items')),stockMovements:@json(url('/api/stock-movements')),cash:@json(url('/api/cash')),reservations:@json(url('/api/reservations')),packages:@json(url('/api/packages')),currentAccounts:@json(url('/api/current-accounts')),sms:@json(url('/api/sms'))};
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
    const setStartMenuOpen=open=>{startMenu.classList.toggle('open',open);document.body.classList.toggle('start-menu-open',open);startButton.setAttribute('aria-expanded',String(open));};
    startButton.setAttribute('aria-controls','start-menu');startButton.setAttribute('aria-expanded','false');
    startButton.addEventListener('click',event=>{event.stopPropagation();setStartMenuOpen(!startMenu.classList.contains('open'));});
    const wallpaperPicker=document.getElementById('wallpaper-picker'),wallpaperButton=document.getElementById('wallpaper-menu-button');
    const applyWallpaper=file=>{const matte=file==='matte-green';document.body.style.backgroundColor='#09261f';document.body.style.backgroundImage=matte?'radial-gradient(ellipse at 52% 48%,#245849 0%,#123c32 58%,#09261f 100%)':`url('${file}')`;document.body.style.backgroundSize='cover';document.body.style.backgroundPosition='center';localStorage.setItem('hastaDesktopWallpaper',file);document.querySelectorAll('[data-wallpaper]').forEach(button=>button.classList.toggle('active',button.dataset.wallpaper===file));};
    wallpaperButton.addEventListener('click',event=>{event.preventDefault();event.stopPropagation();wallpaperPicker.classList.toggle('open');});
    document.querySelectorAll('[data-wallpaper]').forEach(button=>button.addEventListener('click',()=>{applyWallpaper(button.dataset.wallpaper);wallpaperPicker.classList.remove('open');}));
    applyWallpaper('matte-green');
    const applyScreenTheme=theme=>{document.body.classList.remove('theme-gray','theme-green','theme-blue','theme-sofitel');document.body.classList.add('theme-'+theme);localStorage.setItem('voxScreenTheme',theme);document.querySelectorAll('[data-theme]').forEach(button=>button.classList.toggle('active',button.dataset.theme===theme));};
    document.querySelectorAll('[data-theme]').forEach(button=>button.addEventListener('click',()=>{applyScreenTheme(button.dataset.theme);wallpaperPicker.classList.remove('open');}));
    applyScreenTheme('sofitel');
    document.getElementById('logout-button').addEventListener('click',async event=>{event.preventDefault();try{if(authenticated){const payload=await apiFetch(endpoints.logout,{method:'POST',body:'{}'});csrfToken=payload.csrfToken;document.querySelector('meta[name="csrf-token"]').content=csrfToken;}}finally{accessToken=null;memberRecords=[];setStartMenuOpen(false);document.getElementById('login-password').value='';loginScreen.classList.remove('hidden');setTimeout(()=>loginUser.focus(),330);}});
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
    const memberRowsMarkup=()=>memberRecords.map(member=>`<tr data-member-id="${member.id}" data-search="${attr(`${member.memberNo} ${member.name} ${member.phone||''}`).toLocaleLowerCase('tr-TR')}" data-status="${attr(member.status)}"><td>${attr(member.memberNo)}</td><td>${attr(member.name)}</td><td>${attr(formatPhone(member.phone))}</td><td>${attr(member.membershipType)}</td><td>${attr(displayDate(member.validThrough))}</td><td><span class="status-badge ${member.status==='aktif'?'active':'passive'}">${member.status==='aktif'?'Aktif':'Pasif'}</span></td></tr>`).join('');
    const patientListMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="aux-content"><div class="aux-toolbar"><button data-list-action="refresh">🔄 Listele</button><button data-list-action="card">▣ Üye Kartı</button><button data-list-action="excel">▧ Excel'e Aktar</button></div><div class="patient-list-shell"><div class="patient-list-filters"><label>Üye Ara:<input type="search" data-patient-search placeholder="Üye no, ad veya telefon"></label><label>Durum:<span class="select-shell"><select data-patient-status><option value="">Tümü</option><option value="aktif">Aktif</option><option value="pasif">Pasif</option></select><span class="select-arrow">▼</span></span></label></div><div class="patient-grid-wrap"><table class="aux-grid patient-table"><colgroup><col style="width:85px"><col style="width:190px"><col style="width:125px"><col style="width:125px"><col style="width:130px"><col style="width:90px"></colgroup><thead><tr><th>Üye No</th><th>Ad Soyad</th><th>Telefon</th><th>Üyelik Türü</th><th>Geçerlilik Bitişi</th><th>Durum</th></tr></thead><tbody>${memberRowsMarkup()}</tbody></table></div><div class="list-pager"><button title="İlk sayfa">|◀</button><button title="Önceki sayfa">◀</button><span>Sayfa</span><input value="1" size="2"><span>/ 1</span><button title="Sonraki sayfa">▶</button><button title="Son sayfa">▶|</button><button data-list-action="refresh" title="Yenile">🔄</button><span class="list-count">Gösterilen ${memberRecords.length}</span></div></div></div>`;
    const memberCardPayloads={};
    const attr=value=>String(value??'').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    const formatPhone=value=>{
      let digits=String(value??'').replace(/\D/g,'');
      if(digits.startsWith('90')&&digits.length>=12)digits='0'+digits.slice(2,12);
      else if(digits.length===10)digits='0'+digits;
      digits=digits.slice(0,11);
      if(!digits)return'';
      let formatted=digits.slice(0,1);
      if(digits.length>1)formatted+=` (${digits.slice(1,4)}`;
      if(digits.length>=4)formatted+=')';
      if(digits.length>4)formatted+=` ${digits.slice(4,7)}`;
      if(digits.length>7)formatted+=` ${digits.slice(7,9)}`;
      if(digits.length>9)formatted+=` ${digits.slice(9,11)}`;
      return formatted;
    };
    document.addEventListener('input',event=>{if(event.target.matches('[data-phone-input]'))event.target.value=formatPhone(event.target.value);});
    const memberCardMarkup=(title,member={})=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="member-card-content"><form class="member-card-form"><nav class="member-card-tabs" aria-label="Üye kartı bölümleri"><button type="button" class="active" data-member-tab="personal"><span class="member-card-tab-icon">ⓘ</span><span>Bilgiler</span></button><button type="button" data-member-tab="membership"><span class="member-card-tab-icon">§</span><span>Üyelik</span></button><button type="button" data-member-tab="accounting"><span class="member-card-tab-icon">₺</span><span>Muhasebe</span></button></nav><div class="member-card-toolbar"><button type="submit">💾 Kaydet</button><button type="button" data-member-action="close">✕ Kapat</button><span class="member-card-status" role="status"></span></div><div class="member-card-hint">Üye bilgilerini düzenleyip Kaydet düğmesine basın.</div>
      <fieldset class="member-card-section member-card-panel" data-member-panel="personal"><legend>KİŞİSEL BİLGİLER / PERSONAL INFORMATION</legend><div class="member-card-grid">
        <label>Üyelik No / Membership No</label><input name="memberNo" value="${attr(member.memberNo)}">
        <label>Adı Soyadı / Name Last Name</label><input name="name" value="${attr(member.name)}" required>
        <label>TC Kimlik No / Identity Number</label><input name="identity" value="${attr(member.identity)}" maxlength="11">
        <label>Mesleği / Occupation</label><input name="occupation" value="${attr(member.occupation)}">
        <label>Doğum Tarihi / Date Of Birth</label><input name="birthDate" type="date" value="${attr(member.birthDate)}">
        <label>Cep Tel / Mobile Phone</label><input name="phone" type="tel" inputmode="numeric" maxlength="17" data-phone-input placeholder="0 (XXX) XXX XX XX" value="${attr(formatPhone(member.phone))}">
        <label class="wide-label">Adres / Address</label><textarea class="wide-field" name="address">${attr(member.address)}</textarea>
        <label>E-Posta / E-mail</label><input name="email" type="email" value="${attr(member.email)}">
        <label>Acil Durumda Aranacak Kişi</label><input name="emergencyName" value="${attr(member.emergencyName)}">
        <label>Emergency Contact – Phone</label><input name="emergencyPhone" type="tel" inputmode="numeric" maxlength="17" data-phone-input placeholder="0 (XXX) XXX XX XX" value="${attr(formatPhone(member.emergencyPhone))}">
        <label>Durum / Status</label><select name="status"><option value="aktif" ${member.status==='aktif'?'selected':''}>Aktif</option><option value="pasif" ${member.status==='pasif'?'selected':''}>Pasif</option></select>
      </div></fieldset>
      <fieldset class="member-card-section member-card-panel" data-member-panel="membership" hidden><legend>ÜYELİK BİLGİLERİ / MEMBERSHIP INFO</legend><div class="member-card-grid">
        <label>Üyelik Türü / Membership Type</label><input name="membershipType" value="${attr(member.membershipType)}">
        <label>Süresi / Duration (Ay)</label><input name="durationMonths" type="number" min="0" value="${attr(member.durationMonths)}">
        <label>Başlangıç / Valid From</label><input name="validFrom" type="date" value="${attr(member.validFrom)}">
        <label>Bitiş / Through</label><input name="validThrough" type="date" value="${attr(member.validThrough)}">
      </div></fieldset>
      <fieldset class="member-card-section member-card-panel" data-member-panel="accounting" hidden><legend>ÖDEME TAAHHÜTNAMESİ / PAYMENT TERMS</legend><div class="member-card-grid">
        <label>Ödeme Şekli / Payment Type</label><select name="paymentType"><option ${member.paymentType==='Nakit'?'selected':''}>Nakit</option><option ${member.paymentType==='Kredi Kartı'?'selected':''}>Kredi Kartı</option><option ${member.paymentType==='Havale'?'selected':''}>Havale</option></select>
        <label>Sözleşme Bedeli / Contract Amount</label><input name="contractAmount" type="number" min="0" step="0.01" value="${attr(member.contractAmount)}">
        <label class="wide-label">Fatura Adresi / Invoice Address</label><textarea class="wide-field" name="invoiceAddress">${attr(member.invoiceAddress)}</textarea>
      </div></fieldset></form></div>`;
    let employeeRecords=[],occupationRecords=[],workGroupRecords=[];
    const employeeCardPayloads={};
    const employeeName=employee=>`${employee.first_name||''} ${employee.last_name||''}`.trim();
    const employeeRowsMarkup=()=>employeeRecords.map(employee=>`<tr data-employee-id="${employee.id}" data-search="${attr(`${employee.id} ${employeeName(employee)} ${employee.registry_no||''} ${employee.personnel_no||''}`.toLocaleLowerCase('tr-TR'))}"><td>${employee.id}</td><td>${attr(employee.first_name)}</td><td>${attr(employee.last_name)}</td><td>${attr(employee.registry_no||'')}</td><td>${attr(employee.personnel_no||'')}</td><td>${attr(displayDate(employee.hire_date))}</td><td><span class="status-badge ${employee.status==='aktif'?'active':'passive'}">${employee.status==='aktif'?'Aktif':employee.status==='ayrıldı'?'Ayrıldı':'Yasaklı'}</span></td></tr>`).join('');
    const employeeListMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="aux-content"><div class="aux-toolbar"><button type="button" data-employee-action="new">⊕ Yeni Personel</button><button type="button" data-employee-action="refresh">🔄 Listele</button><span class="shift-message" role="status"></span></div><div class="employee-list-shell"><div class="patient-list-filters"><label>Personel Ara:<input type="search" data-employee-search placeholder="ID, ad, sicil veya personel no"></label><label>Durum:<span class="select-shell"><select data-employee-status><option value="">Tümü</option><option value="aktif">Aktif</option><option value="ayrıldı">Ayrıldı</option><option value="yasaklı">Yasaklı</option></select><span class="select-arrow">▼</span></span></label></div><div class="employee-grid-wrap"><table class="aux-grid employee-table"><thead><tr><th>ID</th><th>Ad</th><th>Soyad</th><th>Sicil No</th><th>Personel No</th><th>Giriş Tarihi</th><th>Durum</th></tr></thead><tbody><tr><td colspan="7">Personel listesi yükleniyor…</td></tr></tbody></table></div><div class="list-pager"><span class="list-count">Gösterilen 0</span></div></div></div>`;
    const employeeCardMarkup=(title,employee={})=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="employee-card-content"><form class="employee-card-form"><div class="employee-card-toolbar"><button type="submit">💾 Kaydet</button><span class="employee-card-status" role="status"></span></div><div class="employee-main"><aside class="employee-photo-panel"><div class="employee-photo">${employee.photo_url?`<img src="${attr(employee.photo_url)}" alt="Personel fotoğrafı">`:'👤'}</div><label class="employee-photo-upload">📷 Fotoğraf Seç<input type="file" name="photo_file" accept="image/jpeg,image/png,image/webp"></label><div class="employee-side-tabs"><button type="button" class="active" data-employee-tab="personal">Kişisel Bilgileri</button><button type="button" data-employee-tab="contact">İletişim Bilgileri</button><button type="button">Belgeler</button></div></aside><div><div class="employee-fields employee-tab-panel" data-employee-panel="personal"><label>Ad</label><input name="first_name" value="${attr(employee.first_name)}" required><label>Soyad</label><input name="last_name" value="${attr(employee.last_name)}" required><label>Personel No</label><input name="personnel_no" value="${attr(employee.personnel_no)}"><label>Mesleği</label><select name="occupation_id"><option value="">Seçiniz</option>${occupationRecords.map(item=>`<option value="${item.id}" ${Number(employee.occupation_id)===Number(item.id)?'selected':''}>${attr(item.name)}</option>`).join('')}</select><label>Çalışma Grubu</label><select name="work_group_id"><option value="">Seçiniz</option>${workGroupRecords.map(item=>`<option value="${item.id}" ${Number(employee.work_group_id)===Number(item.id)?'selected':''}>${attr(item.name)}</option>`).join('')}</select><label>Giriş Tarihi</label><input name="hire_date" type="date" value="${attr(employee.hire_date)}"><label>Çıkış Tarihi</label><input name="termination_date" type="date" value="${attr(employee.termination_date)}"><label>Doğum Tarihi</label><input name="birth_date" type="date" value="${attr(employee.birth_date)}"><label>Kan Grubu</label><select name="blood_group"><option value="">Seçiniz</option>${['A+','A-','B+','B-','AB+','AB-','0+','0-'].map(value=>`<option ${employee.blood_group===value?'selected':''}>${value}</option>`).join('')}</select><label>Cinsiyet</label><select name="gender"><option value="">Seçiniz</option>${['Kadın','Erkek','Belirtmek İstemiyor'].map(value=>`<option ${employee.gender===value?'selected':''}>${value}</option>`).join('')}</select><label>Durum</label><select name="status"><option value="aktif" ${!employee.status||employee.status==='aktif'?'selected':''}>Aktif</option><option value="ayrıldı" ${employee.status==='ayrıldı'?'selected':''}>Ayrıldı</option><option value="yasaklı" ${employee.status==='yasaklı'?'selected':''}>Yasaklı</option></select></div><div class="employee-fields employee-contact-fields employee-tab-panel" data-employee-panel="contact" hidden><div class="employee-card-section-title">✉ İletişim Bilgileri</div><label>Telefon 1</label><input name="phone" type="tel" inputmode="numeric" maxlength="17" data-phone-input placeholder="0 (XXX) XXX XX XX" value="${attr(formatPhone(employee.phone))}"><label>Cep Telefonu</label><input name="mobile_phone" type="tel" inputmode="numeric" maxlength="17" data-phone-input placeholder="0 (XXX) XXX XX XX" value="${attr(formatPhone(employee.mobile_phone))}"><label>E-mail</label><input name="email" type="email" value="${attr(employee.email)}"><label>İl</label><input name="city" value="${attr(employee.city)}"><label>İlçe</label><input name="district" value="${attr(employee.district)}"><label>Adres</label><textarea name="address">${attr(employee.address)}</textarea></div></div></div></form></div>`;
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
    const smsSetupPanelMarkup=()=>`<section class="sms-setup-panel setup-panel" data-setup-panel="sms" hidden><div class="aux-toolbar"><button type="button" data-sms-action="save-settings">💾 Ayarları Kaydet</button><button type="button" data-sms-action="refresh">🔄 Yenile</button><span class="sms-message shift-message" role="status"></span></div><div class="sms-setup-grid"><article class="sms-card"><h3>Verimor Hesap Ayarları</h3><form class="sms-form" data-sms-settings-form><label>Servis Sağlayıcı</label><input value="Verimor" readonly><label>API Kullanıcı Adı</label><input name="username" autocomplete="username"><label>API Şifresi</label><input name="password" type="password" autocomplete="new-password" placeholder="Değiştirmeyecekseniz boş bırakın"><label>Gönderici Başlığı</label><input name="source_addr" maxlength="20" placeholder="Örn. SOFITELSPA"><label>Entegrasyon</label><label class="sms-check"><input name="active" type="checkbox"> Etkin</label></form><p class="sms-note">API şifresi uygulama anahtarıyla şifrelenerek saklanır ve ekranda tekrar gösterilmez.</p><p class="sms-note"><strong>Otomatik bildirim:</strong> Bir rezervasyonun terapisti veya saati değiştiğinde 0 (543) 548 01 22 numarasına eski ve yeni bilgiler gönderilir.</p></article><article class="sms-card"><h3>SMS Gönderimi</h3><form class="sms-form" data-sms-test-form><label>Alıcı Adı</label><input name="recipient_name" placeholder="Ad Soyad"><label>Telefon *</label><input name="destination" type="tel" inputmode="numeric" maxlength="17" data-phone-input placeholder="0 (XXX) XXX XX XX" required><label>Mesaj *</label><textarea name="message" maxlength="1071" required>Merhaba, Sofitel Spa SMS entegrasyonu başarıyla çalışıyor.</textarea><label>İleti Türü</label><label class="sms-check"><input name="is_commercial" type="checkbox"> Ticari ileti (İYS)</label></form><div class="sms-test-actions"><button type="button" data-sms-action="send">✉ SMS Gönder</button><span data-sms-character-count>0 karakter</span></div><p class="sms-note">Bu düğme gerçek bir SMS gönderir ve Verimor bakiyenizden kullanım yapar.</p></article><article class="sms-card sms-log"><h3>Son SMS Gönderimleri</h3><div class="shift-grid-wrap"><table class="aux-grid sms-log-table"><thead><tr><th>Tarih</th><th>Alıcı</th><th>Telefon</th><th>Mesaj</th><th>Kampanya</th><th>Durum</th></tr></thead><tbody><tr><td colspan="6">SMS kayıtları yükleniyor…</td></tr></tbody></table></div></article></div></section>`;
    const setupMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="aux-content"><div class="setup-tabs"><button type="button" class="setup-tab active" data-setup-tab="shifts">Mesai Tanımları</button><button type="button" class="setup-tab" data-setup-tab="business-hours">Çalışma Saatleri</button><button type="button" class="setup-tab" data-setup-tab="occupations">Meslekler</button><button type="button" class="setup-tab" data-setup-tab="work-groups">Çalışma Grupları</button><button type="button" class="setup-tab" data-setup-tab="categories">Kategoriler</button><button type="button" class="setup-tab" data-setup-tab="sms">SMS Ayarları</button></div><section class="shift-panel setup-panel" data-setup-panel="shifts"><div class="aux-toolbar"><button type="button" data-shift-action="new">⊕ Yeni Mesai</button><button type="button" data-shift-action="refresh">🔄 Yenile</button><span class="shift-message" role="status"></span></div><div class="shift-grid-wrap"><table class="aux-grid shift-table"><thead><tr><th>Kod</th><th>Mesai Saatleri</th><th>İşlemler</th></tr></thead><tbody><tr><td colspan="3">Mesailer yükleniyor…</td></tr></tbody></table></div></section><section class="business-hours-panel setup-panel" data-setup-panel="business-hours" hidden><div class="aux-toolbar"><button type="button" data-business-hours-action="save">💾 Çalışma Saatlerini Kaydet</button><button type="button" data-business-hours-action="refresh">🔄 Yenile</button><span class="business-hours-message shift-message" role="status"></span></div><div class="business-hours-grid-wrap"><table class="aux-grid business-hours-table"><thead><tr><th>Gün</th><th>Açılış</th><th>Kapanış</th><th>Kapalı</th></tr></thead><tbody><tr><td colspan="4">Çalışma saatleri yükleniyor…</td></tr></tbody></table></div></section>${definitionPanelMarkup('occupations','Meslek')}${definitionPanelMarkup('work-groups','Çalışma Grubu')}${definitionPanelMarkup('categories','Kategori')}${smsSetupPanelMarkup()}</div>`;
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
    const setupSmsWindow=win=>{
      const panel=win.querySelector('[data-setup-panel="sms"]'),message=panel.querySelector('.sms-message'),settingsForm=panel.querySelector('[data-sms-settings-form]'),testForm=panel.querySelector('[data-sms-test-form]'),logBody=panel.querySelector('.sms-log-table tbody'),characterCount=panel.querySelector('[data-sms-character-count]');
      const statusLabel=status=>({pending:'Bekliyor',submitted:'Gönderildi',failed:'Başarısız'}[status]||status);
      const updateCharacterCount=()=>{characterCount.textContent=`${testForm.elements.message.value.length} karakter`;};
      const render=data=>{
        const settings=data.settings||{};
        settingsForm.elements.username.value=settings.username||'';
        settingsForm.elements.password.value='';
        settingsForm.elements.password.placeholder=settings.has_password?'Kayıtlı şifreyi değiştirmek için yazın':'API şifresini girin';
        settingsForm.elements.source_addr.value=settings.source_addr||'';
        settingsForm.elements.active.checked=Boolean(settings.active);
        const messages=data.messages||[];
        logBody.innerHTML=messages.length?messages.map(item=>`<tr><td>${attr(item.sent_at?new Date(item.sent_at).toLocaleString('tr-TR'):'—')}</td><td>${attr(item.recipient_name||'—')}</td><td>${attr(formatPhone(item.destination))}</td><td title="${attr(item.message)}">${attr(item.message.length>70?item.message.slice(0,70)+'…':item.message)}</td><td>${attr(item.campaign_id||'—')}</td><td>${attr(statusLabel(item.status))}</td></tr>`).join(''):'<tr><td colspan="6">Henüz SMS gönderim kaydı bulunmuyor.</td></tr>';
        updateCharacterCount();
      };
      const refresh=async()=>{message.textContent='SMS ayarları yükleniyor…';try{const payload=await apiFetch(endpoints.sms);render(payload.data);message.textContent=payload.data.settings.active?'Verimor SMS entegrasyonu etkin.':'Verimor SMS entegrasyonu kapalı.';}catch(error){message.textContent=error.message;}};
      panel.querySelector('[data-sms-action="refresh"]').onclick=refresh;
      panel.querySelector('[data-sms-action="save-settings"]').onclick=async()=>{const values=Object.fromEntries(new FormData(settingsForm));values.active=settingsForm.elements.active.checked;message.textContent='SMS ayarları kaydediliyor…';try{const payload=await apiFetch(`${endpoints.sms}/settings`,{method:'PUT',body:JSON.stringify(values)});render({settings:payload.data,messages:[]});await refresh();message.textContent='SMS ayarları güvenli biçimde kaydedildi.';}catch(error){message.textContent=error.message;}};
      panel.querySelector('[data-sms-action="send"]').onclick=async()=>{if(!testForm.reportValidity())return;if(!confirm('Bu işlem gerçek bir SMS gönderir ve Verimor bakiyenizi kullanır. Devam edilsin mi?'))return;const values=Object.fromEntries(new FormData(testForm));values.is_commercial=testForm.elements.is_commercial.checked;if(values.is_commercial)values.iys_recipient_type='BIREYSEL';message.textContent='SMS Verimor’a gönderiliyor…';try{const payload=await apiFetch(`${endpoints.sms}/send`,{method:'POST',body:JSON.stringify(values)});await refresh();message.textContent=`SMS gönderim isteği kabul edildi. Kampanya: ${payload.data.campaign_id||'—'}`;}catch(error){message.textContent=error.message;}};
      testForm.elements.message.addEventListener('input',updateCharacterCount);
      updateCharacterCount();
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
        if(tab.dataset.setupTab==='categories')setupDefinitionWindow(win,'categories','Kategori',endpoints.categories);
        if(tab.dataset.setupTab==='sms')setupSmsWindow(win);
      });
    };
    let scheduleWeekStart='';
    const localIso=date=>`${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(date.getDate()).padStart(2,'0')}`;
    const mondayOf=date=>{const result=new Date(date.getFullYear(),date.getMonth(),date.getDate(),12),day=(result.getDay()+6)%7;result.setDate(result.getDate()-day);return result;};
    const isoWeekNumber=date=>{const target=new Date(Date.UTC(date.getFullYear(),date.getMonth(),date.getDate())),day=target.getUTCDay()||7;target.setUTCDate(target.getUTCDate()+4-day);const yearStart=new Date(Date.UTC(target.getUTCFullYear(),0,1));return Math.ceil((((target-yearStart)/86400000)+1)/7);};
    const scheduleMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="schedule-shell"><div class="schedule-print-header"><img class="schedule-print-logo" src="{{ asset('Sofitelspa-transparent.png') }}" alt="Sofitel Spa"><div class="schedule-print-title"><h1>HAFTALIK ÇALIŞMA PROGRAMI</h1><span class="schedule-print-week-label">Hafta yükleniyor…</span></div></div><div class="schedule-toolbar"><button type="button" data-schedule-action="previous">◀ Önceki Hafta</button><button type="button" data-schedule-action="today">Bu Hafta</button><strong class="schedule-week-label">Hafta yükleniyor…</strong><button type="button" data-schedule-action="next">Sonraki Hafta ▶</button><button type="button" data-schedule-action="save">💾 Haftayı Kaydet</button><button type="button" data-schedule-action="print">🖨 Yazdır</button><label><span class="sr-only">Çalışma Grubu</span><select data-schedule-group aria-label="Çalışma Grubu"><option value="">Çalışma grubunu seçiniz</option></select></label><span class="shift-message" role="status"></span></div><div class="schedule-grid-wrap"><table class="schedule-table"><tbody><tr><td class="schedule-empty">Çalışma grubu seçiniz.</td></tr></tbody></table></div></div>`;
    const scheduleChoice=(assignment,shifts,businessHour)=>{const value=assignment?.work_shift_id?`shift:${assignment.work_shift_id}`:(assignment?.status||''),availableShifts=businessHour&&!businessHour.is_closed?shifts.filter(shift=>shortTime(shift.start_time)>=shortTime(businessHour.opening_time)&&shortTime(shift.end_time)<=shortTime(businessHour.closing_time)):[];return `<option value="">— Seçiniz —</option>${availableShifts.map(shift=>`<option value="shift:${shift.id}" ${value===`shift:${shift.id}`?'selected':''}>${attr(shortTime(shift.start_time))}-${attr(shortTime(shift.end_time))}</option>`).join('')}<option value="off" ${value==='off'?'selected':''}>OFF</option><option value="izin" ${value==='izin'?'selected':''}>İZİN</option><option value="raporlu" ${value==='raporlu'?'selected':''}>RAPORLU</option>`;};
    const renderSchedule=(win,data)=>{scheduleWeekStart=data.week_start;const start=new Date(`${data.week_start}T12:00:00`),days=Array.from({length:7},(_,index)=>{const day=new Date(start);day.setDate(start.getDate()+index);return day;}),dayNames=['Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi','Pazar'],businessHourMap=new Map((data.business_hours||[]).map(hour=>[Number(hour.day_of_week),hour])),assignmentMap=new Map(data.assignments.map(item=>[`${item.employee_id}:${String(item.work_date).slice(0,10)}`,item])),groupSelect=win.querySelector('[data-schedule-group]'),selectedGroup=groupSelect.value,groups=[...new Map(data.employees.filter(employee=>employee.work_group).map(employee=>[String(employee.work_group.id),employee.work_group])).values()].sort((a,b)=>a.name.localeCompare(b.name,'tr')),employees=selectedGroup?data.employees.filter(employee=>selectedGroup==='ungrouped'?!employee.work_group_id:String(employee.work_group_id)===selectedGroup):[],weekLabel=`${isoWeekNumber(start)}. Hafta • ${displayDate(localIso(days[0]))} – ${displayDate(localIso(days[6]))}`;groupSelect.innerHTML=`<option value="">Çalışma grubunu seçiniz</option>${groups.map(group=>`<option value="${group.id}" ${selectedGroup===String(group.id)?'selected':''}>${attr(group.name)}</option>`).join('')}<option value="ungrouped" ${selectedGroup==='ungrouped'?'selected':''}>Grupsuz Personel</option>`;win.querySelector('.schedule-week-label').textContent=weekLabel;win.querySelector('.schedule-print-week-label').textContent=selectedGroup?`${weekLabel} • ${groupSelect.options[groupSelect.selectedIndex].text}`:weekLabel;const head=`<thead><tr><th>PERSONEL</th>${days.map((day,index)=>`<th><span class="schedule-day-name">${dayNames[index]}</span><span class="schedule-day-date">${displayDate(localIso(day))}</span></th>`).join('')}</tr></thead>`,employeeRow=employee=>`<tr><th>${attr(employeeName(employee))}</th>${days.map(day=>{const date=localIso(day),dayOfWeek=((day.getDay()+6)%7)+1,assignment=assignmentMap.get(`${employee.id}:${date}`),state=assignment?.status?` state-${assignment.status}`:'';return `<td class="schedule-cell${state}"><select data-employee-id="${employee.id}" data-work-date="${date}" aria-label="${attr(employeeName(employee))} ${displayDate(date)}">${scheduleChoice(assignment,data.work_shifts,businessHourMap.get(dayOfWeek))}</select></td>`;}).join('')}</tr>`,grouped=new Map();employees.forEach(employee=>{const key=employee.work_group?.name||'Grupsuz Personel';if(!grouped.has(key))grouped.set(key,[]);grouped.get(key).push(employee);});const body=!selectedGroup?`<tr><td colspan="8" class="schedule-empty">Çalışma programı oluşturmak için çalışma grubu seçiniz.</td></tr>`:employees.length?[...grouped.entries()].map(([groupName,members])=>`<tr class="schedule-group-row"><th colspan="8">${attr(groupName)}</th></tr>${members.map(employeeRow).join('')}`).join(''):`<tr><td colspan="8" class="schedule-empty">Bu çalışma grubunda aktif personel bulunamadı.</td></tr>`;win.querySelector('.schedule-table').innerHTML=head+`<tbody>${body}</tbody>`;};
    const setupScheduleWindow=win=>{const message=win.querySelector('.shift-message');let scheduleData=null;const load=async week=>{message.textContent='Plan yükleniyor…';try{const payload=await apiFetch(`${endpoints.employeeSchedules}?week_start=${week}`);scheduleData=payload.data;renderSchedule(win,scheduleData);message.textContent=`${payload.data.employees.length} personel • ${payload.data.work_shifts.length} mesai`;}catch(error){message.textContent=error.message;}};const move=days=>{const date=new Date(`${scheduleWeekStart}T12:00:00`);date.setDate(date.getDate()+days);load(localIso(date));};win.querySelector('[data-schedule-group]').onchange=()=>scheduleData&&renderSchedule(win,scheduleData);win.querySelector('[data-schedule-action="previous"]').onclick=()=>move(-7);win.querySelector('[data-schedule-action="next"]').onclick=()=>move(7);win.querySelector('[data-schedule-action="today"]').onclick=()=>load(localIso(mondayOf(new Date())));win.querySelector('[data-schedule-action="print"]').onclick=()=>window.print();win.querySelector('[data-schedule-action="save"]').onclick=async()=>{if(!win.querySelector('[data-schedule-group]').value){message.textContent='Önce çalışma grubu seçiniz.';return;}const selects=[...win.querySelectorAll('.schedule-cell select')],employee_ids=[...new Set(selects.map(select=>Number(select.dataset.employeeId)))],assignments=selects.map(select=>{const value=select.value;return {employee_id:Number(select.dataset.employeeId),work_date:select.dataset.workDate,work_shift_id:value.startsWith('shift:')?Number(value.slice(6)):null,status:value&&!value.startsWith('shift:')?value:null};});message.textContent='Hafta kaydediliyor…';try{const payload=await apiFetch(endpoints.employeeSchedules,{method:'PUT',body:JSON.stringify({week_start:scheduleWeekStart,employee_ids,assignments})});scheduleData=payload.data;renderSchedule(win,scheduleData);message.textContent='Seçili çalışma grubunun haftalık programı kaydedildi.';}catch(error){message.textContent=error.message;}};win.querySelector('.schedule-grid-wrap').addEventListener('change',event=>{const select=event.target.closest('.schedule-cell select');if(!select)return;select.parentElement.className=`schedule-cell${['off','izin','raporlu'].includes(select.value)?` state-${select.value}`:''}`;});load(localIso(mondayOf(new Date())));};
    let stockRecords=[],stockMovementRecords=[],stockCategoryRecords=[];
    const stockTabs=[['list','▤','Stok Listesi'],['card','▣','Stok Kartı'],['entry','⇥','Stok Giriş'],['exit','⇤','Stok Çıkış']];
    const stockItemOptions=selected=>`<option value="">Stok kartını seçiniz</option>${stockRecords.filter(item=>item.status==='aktif'||Number(item.id)===Number(selected)).map(item=>`<option value="${item.id}" ${Number(item.id)===Number(selected)?'selected':''}>${attr(item.code)} — ${attr(item.name)} (${Number(item.quantity||0).toLocaleString('tr-TR')} ${attr(item.unit)})</option>`).join('')}`;
    const stockActionIcon=type=>type==='edit'?'<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3 11.8 2.5 14l2.2-.5 7.6-7.6-1.7-1.7zM9.8 5l1.7 1.7 1.5-1.5a1.2 1.2 0 0 0 0-1.7l-.1-.1a1.2 1.2 0 0 0-1.7 0z"/><path d="M8.5 3H4a2 2 0 0 0-2 2v6"/></svg>':type==='history'?'<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3 5V2.8L1.5 4.3M3 4a6 6 0 1 1-1 7M8 4.5V8l2.4 1.5"/></svg>':'<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3.5 4.5h9M6 2.5h4l.7 2H5.3zM4.7 4.5l.6 9h5.4l.6-9M7 7v4M9 7v4"/></svg>';
    const stockListRows=()=>stockRecords.length?stockRecords.map(item=>{const quantity=Number(item.quantity||0),critical=quantity<=Number(item.minimum_quantity||0),categories=item.categories||[],categoryNames=categories.map(category=>category.name).join(', '),categoryIds=categories.map(category=>category.id).join(',');return`<tr data-stock-id="${item.id}" data-search="${attr(`${item.code} ${item.name} ${categoryNames} ${item.brand||''}`.toLocaleLowerCase('tr-TR'))}" data-category-ids="${attr(categoryIds)}"><td>${attr(item.code)}</td><td>${attr(item.name)}</td><td>${attr(categoryNames||'—')}</td><td>${attr(item.brand||'—')}</td><td>${attr(item.unit)}</td><td class="${critical?'critical':''}">${quantity.toLocaleString('tr-TR')}</td><td>${Number(item.minimum_quantity||0).toLocaleString('tr-TR')}</td><td>${Number(item.sale_price||0).toLocaleString('tr-TR',{minimumFractionDigits:2})} ₺</td><td>${item.status==='aktif'?'Aktif':'Pasif'}</td><td><span class="stock-row-actions"><button type="button" data-stock-action="edit" title="Düzenle" aria-label="${attr(item.name)} stok kartını düzenle">${stockActionIcon('edit')}</button><button type="button" data-stock-action="history" title="Hareketler" aria-label="${attr(item.name)} stok hareketlerini aç">${stockActionIcon('history')}</button><button type="button" data-stock-action="delete" title="Sil" aria-label="${attr(item.name)} stok kartını sil">${stockActionIcon('delete')}</button></span></td></tr>`}).join(''):'<tr><td colspan="10" class="stock-empty">Henüz stok kartı bulunmuyor.</td></tr>';
    const stockCardListRows=()=>stockRecords.length?stockRecords.map(item=>{const categoryNames=(item.categories||[]).map(category=>category.name).join(', '),search=`${item.code} ${item.name} ${categoryNames} ${item.brand||''}`.toLocaleLowerCase('tr-TR');return`<tr data-stock-id="${item.id}" data-stock-card-row data-card-search="${attr(search)}"><td>${attr(item.code)}</td><td>${attr(item.name)}</td><td>${attr(categoryNames||'—')}</td><td>${attr(item.brand||'—')}</td><td>${attr(item.unit)}</td><td>${item.status==='aktif'?'Aktif':'Pasif'}</td><td><span class="stock-row-actions"><button type="button" data-stock-action="edit" title="Düzenle" aria-label="${attr(item.name)} stok kartını düzenle">${stockActionIcon('edit')}</button><button type="button" data-stock-action="delete" title="Sil" aria-label="${attr(item.name)} stok kartını sil">${stockActionIcon('delete')}</button></span></td></tr>`}).join(''):'<tr><td colspan="7" class="stock-empty">Henüz stok kartı bulunmuyor. Yeni Kart düğmesiyle ilk kartı oluşturabilirsiniz.</td></tr>';
    const stockCardListMarkup=()=>`<div class="stock-card-list-head"><strong>Stok Kartı Listesi</strong><span>${stockRecords.length} kart</span></div><div class="stock-filters"><input type="search" data-stock-card-search placeholder="Stok kartı kodu, adı, kategori veya marka ara"></div><div class="stock-table-wrap"><table class="stock-table"><thead><tr><th>Stok Kodu</th><th>Stok Adı</th><th>Kategoriler</th><th>Marka</th><th>Birim</th><th>Durum</th><th>İşlemler</th></tr></thead><tbody>${stockCardListRows()}</tbody></table></div>`;
    const stockEntryListRows=()=>{const rows=stockMovementRecords.filter(item=>item.type==='giris');return rows.length?rows.map(item=>{const search=`${item.stock_item?.code||''} ${item.stock_item?.name||''} ${item.document_no||''} ${item.description||''}`.toLocaleLowerCase('tr-TR');return`<tr data-stock-movement-id="${item.id}" data-stock-entry-row data-entry-search="${attr(search)}"><td>${attr(displayDate(item.movement_date))}</td><td>${attr(item.stock_item?.code||'')}</td><td>${attr(item.stock_item?.name||'')}</td><td>${Number(item.quantity).toLocaleString('tr-TR')} ${attr(item.stock_item?.unit||'')}</td><td>${attr(item.document_no||'—')}</td><td>${attr(item.description||'—')}</td><td><span class="stock-row-actions"><button type="button" data-stock-movement-action="edit" title="Düzenle" aria-label="Stok girişini düzenle">${stockActionIcon('edit')}</button><button type="button" data-stock-movement-action="delete" title="Sil" aria-label="Stok girişini sil">${stockActionIcon('delete')}</button></span></td></tr>`}).join(''):'<tr><td colspan="7" class="stock-empty">Henüz stok giriş kaydı bulunmuyor. Yeni Giriş düğmesiyle ilk kaydı oluşturabilirsiniz.</td></tr>'};
    const stockEntryListMarkup=()=>{const count=stockMovementRecords.filter(item=>item.type==='giris').length;return`<div class="stock-card-list-head"><strong>Stok Giriş Listesi</strong><span>${count} giriş</span></div><div class="stock-filters"><input type="search" data-stock-entry-search placeholder="Stok kodu, adı, belge no veya açıklama ara"></div><div class="stock-table-wrap"><table class="stock-table"><thead><tr><th>Tarih</th><th>Stok Kodu</th><th>Stok Adı</th><th>Miktar</th><th>Belge No</th><th>Açıklama</th><th>İşlemler</th></tr></thead><tbody>${stockEntryListRows()}</tbody></table></div>`;};
    const stockExitListRows=()=>{const rows=stockMovementRecords.filter(item=>item.type==='cikis');return rows.length?rows.map(item=>{const search=`${item.stock_item?.code||''} ${item.stock_item?.name||''} ${item.document_no||''} ${item.description||''}`.toLocaleLowerCase('tr-TR');return`<tr data-stock-movement-id="${item.id}" data-stock-movement-type="cikis" data-stock-exit-row data-exit-search="${attr(search)}"><td>${attr(displayDate(item.movement_date))}</td><td>${attr(item.stock_item?.code||'')}</td><td>${attr(item.stock_item?.name||'')}</td><td>${Number(item.quantity).toLocaleString('tr-TR')} ${attr(item.stock_item?.unit||'')}</td><td>${attr(item.document_no||'—')}</td><td>${attr(item.description||'—')}</td><td><span class="stock-row-actions"><button type="button" data-stock-movement-action="edit" title="Düzenle" aria-label="Stok çıkışını düzenle">${stockActionIcon('edit')}</button><button type="button" data-stock-movement-action="delete" title="Sil" aria-label="Stok çıkışını sil">${stockActionIcon('delete')}</button></span></td></tr>`}).join(''):'<tr><td colspan="7" class="stock-empty">Henüz stok çıkış kaydı bulunmuyor. Yeni Çıkış düğmesiyle ilk kaydı oluşturabilirsiniz.</td></tr>'};
    const stockExitListMarkup=()=>{const count=stockMovementRecords.filter(item=>item.type==='cikis').length;return`<div class="stock-card-list-head"><strong>Stok Çıkış Listesi</strong><span>${count} çıkış</span></div><div class="stock-filters"><input type="search" data-stock-exit-search placeholder="Stok kodu, adı, belge no veya açıklama ara"></div><div class="stock-table-wrap"><table class="stock-table"><thead><tr><th>Tarih</th><th>Stok Kodu</th><th>Stok Adı</th><th>Miktar</th><th>Belge No</th><th>Açıklama</th><th>İşlemler</th></tr></thead><tbody>${stockExitListRows()}</tbody></table></div>`;};
    const stockMovementRows=type=>{const rows=stockMovementRecords.filter(item=>item.type===type);return rows.length?rows.map(item=>`<tr><td>${attr(displayDate(item.movement_date))}</td><td>${attr(item.stock_item?.code||'')}</td><td>${attr(item.stock_item?.name||'')}</td><td>${Number(item.quantity).toLocaleString('tr-TR')} ${attr(item.stock_item?.unit||'')}</td><td>${attr(item.document_no||'—')}</td><td>${attr(item.description||'—')}</td></tr>`).join(''):'<tr><td colspan="6" class="stock-empty">Henüz hareket bulunmuyor.</td></tr>'};
    const stockCardForm=(item={})=>{const selectedCategoryIds=new Set((item.categories||[]).map(category=>Number(category.id))),selectedCategoryNames=stockCategoryRecords.filter(category=>selectedCategoryIds.has(Number(category.id))).map(category=>category.name);return`<form class="stock-form" data-stock-card-form data-stock-id="${item.id||''}"><label>Stok Kodu *</label><input name="code" value="${attr(item.code)}" required><label>Stok Adı *</label><input name="name" value="${attr(item.name)}" required><label>Kategoriler</label><div class="stock-category-picker" data-stock-category-picker><button type="button" class="stock-category-trigger" data-stock-category-trigger aria-haspopup="listbox" aria-expanded="false"><span data-stock-category-summary>${attr(selectedCategoryNames.join(', ')||'Kategori seçiniz')}</span><span class="stock-category-trigger-arrow" aria-hidden="true">⌄</span></button><div class="stock-category-menu" data-stock-category-menu role="listbox" aria-label="Stok kategorileri" aria-multiselectable="true" hidden>${stockCategoryRecords.length?stockCategoryRecords.map(category=>`<label><input type="checkbox" name="category_ids[]" value="${category.id}" ${selectedCategoryIds.has(Number(category.id))?'checked':''}>${attr(category.name)}</label>`).join(''):'<span class="stock-category-empty">Önce Kurulum menüsünden kategori tanımlayın.</span>'}</div></div><label>Marka</label><input name="brand" value="${attr(item.brand)}"><label>Birim *</label><select name="unit">${['Adet','Kutu','Paket','Şişe','Tüp','Kilogram','Litre'].map(value=>`<option ${item.unit===value||(!item.unit&&value==='Adet')?'selected':''}>${value}</option>`).join('')}</select><label>Minimum Stok</label><input name="minimum_quantity" type="number" min="0" step="0.01" value="${attr(item.minimum_quantity??0)}"><label>Alış Fiyatı</label><input name="purchase_price" type="number" min="0" step="0.01" value="${attr(item.purchase_price??0)}"><label>Satış Fiyatı</label><input name="sale_price" type="number" min="0" step="0.01" value="${attr(item.sale_price??0)}"><label>KDV Oranı (%)</label><input name="vat_rate" type="number" min="0" max="100" step="0.01" value="${attr(item.vat_rate??20)}"><label>Durum</label><select name="status"><option value="aktif" ${item.status!=='pasif'?'selected':''}>Aktif</option><option value="pasif" ${item.status==='pasif'?'selected':''}>Pasif</option></select><label>Açıklama</label><textarea name="description">${attr(item.description)}</textarea><div></div></form>`;};
    const stockCardEditor=item=>`<div class="stock-card-editor-toolbar"><button type="button" data-stock-card-action="list">← Stok Kartı Listesine Dön</button></div>${stockCardForm(item)}`;
    const stockMovementForm=(type,item={})=>`<form class="stock-form" data-stock-movement-form data-movement-type="${type}" data-stock-movement-id="${item.id||''}"><label>Stok Kartı *</label><select name="stock_item_id" required>${stockItemOptions(item.stock_item_id)}</select><label>Miktar *</label><input name="quantity" type="number" min="0.01" step="0.01" value="${attr(item.quantity)}" required><label>Tarih *</label><input name="movement_date" type="date" value="${attr(item.movement_date?String(item.movement_date).slice(0,10):localIso(new Date()))}" required><label>Belge / Fatura No</label><input name="document_no" value="${attr(item.document_no)}"><label>Açıklama</label><textarea name="description">${attr(item.description)}</textarea><div></div></form>`;
    const stockEntryEditor=item=>`<div class="stock-card-editor-toolbar"><button type="button" data-stock-entry-action="list">← Stok Giriş Listesine Dön</button></div>${stockMovementForm('giris',item)}`;
    const stockExitEditor=item=>`<div class="stock-card-editor-toolbar"><button type="button" data-stock-exit-action="list">← Stok Çıkış Listesine Dön</button></div>${stockMovementForm('cikis',item)}`;
    const stockMovementPanel=type=>`${stockMovementForm(type)}<div class="stock-table-wrap"><table class="stock-table"><thead><tr><th>Tarih</th><th>Stok Kodu</th><th>Stok Adı</th><th>Miktar</th><th>Belge No</th><th>Açıklama</th></tr></thead><tbody>${stockMovementRows(type)}</tbody></table></div>`;
    const stockMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="stock-shell"><nav class="stock-tabs">${stockTabs.map(([key,icon,label],index)=>`<button type="button" class="${index===0?'active':''}" data-stock-tab="${key}"><span>${icon}</span>${label}</button>`).join('')}</nav><div class="stock-toolbar"><button type="button" data-stock-command="new">⊕ Yeni Kart</button><button type="button" data-stock-command="save">💾 Kaydet</button><span class="stock-message" role="status"></span></div><section class="stock-panel" data-stock-panel="list"><div class="stock-filters"><input type="search" data-stock-search placeholder="Stok kodu, adı, kategori veya marka ara"><select data-stock-category><option value="">Tüm kategoriler</option></select></div><div class="stock-table-wrap"><table class="stock-table"><thead><tr><th>Stok Kodu</th><th>Stok Adı</th><th>Kategori</th><th>Marka</th><th>Birim</th><th>Mevcut</th><th>Minimum</th><th>Satış Fiyatı</th><th>Durum</th><th>İşlemler</th></tr></thead><tbody><tr><td colspan="10" class="stock-empty">Stoklar yükleniyor…</td></tr></tbody></table></div></section><section class="stock-panel" data-stock-panel="card" hidden>${stockCardListMarkup()}</section><section class="stock-panel" data-stock-panel="entry" hidden>${stockEntryListMarkup()}</section><section class="stock-panel" data-stock-panel="exit" hidden>${stockExitListMarkup()}</section></div>`;
    const setupStockWindow=win=>{
      const message=win.querySelector('.stock-message');
      const showTab=key=>{
        win.querySelectorAll('[data-stock-tab]').forEach(tab=>tab.classList.toggle('active',tab.dataset.stockTab===key));
        win.querySelectorAll('[data-stock-panel]').forEach(panel=>panel.hidden=panel.dataset.stockPanel!==key);
        const newButton=win.querySelector('[data-stock-command="new"]');
        newButton.textContent=key==='entry'?'⊕ Yeni Giriş':key==='exit'?'⊕ Yeni Çıkış':'⊕ Yeni Kart';
      };
      const showCardList=()=>{
        win.querySelector('[data-stock-panel="card"]').innerHTML=stockCardListMarkup();
        showTab('card');
      };
      const showEntryList=()=>{
        win.querySelector('[data-stock-panel="entry"]').innerHTML=stockEntryListMarkup();
        showTab('entry');
      };
      const showExitList=()=>{
        win.querySelector('[data-stock-panel="exit"]').innerHTML=stockExitListMarkup();
        showTab('exit');
      };
      const closeCategoryMenus=except=>{
        win.querySelectorAll('[data-stock-category-picker]').forEach(picker=>{
          if(picker===except)return;
          picker.querySelector('[data-stock-category-menu]').hidden=true;
          picker.querySelector('[data-stock-category-trigger]').setAttribute('aria-expanded','false');
        });
      };
      const updateCategorySummary=picker=>{
        const names=[...picker.querySelectorAll('input[name="category_ids[]"]:checked')].map(input=>input.closest('label').textContent.trim());
        picker.querySelector('[data-stock-category-summary]').textContent=names.join(', ')||'Kategori seçiniz';
      };
      const render=()=>{
        const listPanel=win.querySelector('[data-stock-panel="list"]'),categories=stockCategoryRecords;
        listPanel.innerHTML=`<div class="stock-filters"><input type="search" data-stock-search placeholder="Stok kodu, adı, kategori veya marka ara"><select data-stock-category><option value="">Tüm kategoriler</option>${categories.map(category=>`<option value="${category.id}">${attr(category.name)}</option>`).join('')}</select></div><div class="stock-table-wrap"><table class="stock-table"><thead><tr><th>Stok Kodu</th><th>Stok Adı</th><th>Kategori</th><th>Marka</th><th>Birim</th><th>Mevcut</th><th>Minimum</th><th>Satış Fiyatı</th><th>Durum</th><th>İşlemler</th></tr></thead><tbody>${stockListRows()}</tbody></table></div>`;
        win.querySelector('[data-stock-panel="card"]').innerHTML=stockCardListMarkup();
        win.querySelector('[data-stock-panel="entry"]').innerHTML=stockEntryListMarkup();
        win.querySelector('[data-stock-panel="exit"]').innerHTML=stockExitListMarkup();
      };
      const load=async()=>{
        message.textContent='Stok bilgileri yükleniyor…';
        try{
          const [items,movements,categories]=await Promise.all([apiFetch(endpoints.stockItems),apiFetch(endpoints.stockMovements),apiFetch(endpoints.categories)]);
          stockRecords=items.data;
          stockMovementRecords=movements.data;
          stockCategoryRecords=categories.data;
          render();
          message.textContent=`${stockRecords.length} stok kartı • ${stockMovementRecords.length} hareket`;
        }catch(error){
          message.textContent=error.message;
        }
      };
      const saveCard=async()=>{
        const form=win.querySelector('[data-stock-panel="card"] [data-stock-card-form]');
        if(!form){
          message.textContent='Kaydetmek için listeden Yeni Kart veya Düzenle seçeneğini açın.';
          return;
        }
        if(!form.reportValidity())return;
        const values=Object.fromEntries(new FormData(form));
        delete values['category_ids[]'];
        values.category_ids=[...form.querySelectorAll('input[name="category_ids[]"]:checked')].map(input=>Number(input.value));
        ['minimum_quantity','purchase_price','sale_price','vat_rate'].forEach(key=>values[key]=Number(values[key]||0));
        const id=form.dataset.stockId;
        message.textContent='Stok kartı kaydediliyor…';
        try{
          await apiFetch(id?`${endpoints.stockItems}/${id}`:endpoints.stockItems,{method:id?'PUT':'POST',body:JSON.stringify(values)});
          await load();
          showTab('card');
          message.textContent='Stok kartı kaydedildi.';
        }catch(error){
          message.textContent=error.message;
        }
      };
      const saveMovement=async type=>{
        const panel=type==='giris'?'entry':'exit';
        const label=type==='giris'?'girişi':'çıkışı';
        const form=win.querySelector(`[data-stock-panel="${panel}"] [data-stock-movement-form]`);
        if(!form){
          message.textContent=`Kaydetmek için Yeni Stok ${type==='giris'?'Girişi':'Çıkışı'} veya Düzenle seçeneğini açın.`;
          return;
        }
        if(!form.reportValidity())return;
        const values=Object.fromEntries(new FormData(form));
        values.stock_item_id=Number(values.stock_item_id);
        values.quantity=Number(values.quantity);
        values.type=type;
        const id=form.dataset.stockMovementId;
        message.textContent=`Stok ${label} ${id?'güncelleniyor':'kaydediliyor'}…`;
        try{
          await apiFetch(id?`${endpoints.stockMovements}/${id}`:endpoints.stockMovements,{method:id?'PUT':'POST',body:JSON.stringify(values)});
          await load();
          if(type==='giris')showEntryList();else showExitList();
          message.textContent=`Stok ${label} ${id?'güncellendi':'kaydedildi'}.`;
        }catch(error){
          message.textContent=error.message;
        }
      };
      win.querySelector('.stock-tabs').addEventListener('click',event=>{
        const tab=event.target.closest('[data-stock-tab]');
        if(!tab)return;
        closeCategoryMenus();
        if(tab.dataset.stockTab==='card')showCardList();
        else if(tab.dataset.stockTab==='entry')showEntryList();
        else if(tab.dataset.stockTab==='exit')showExitList();
        else showTab(tab.dataset.stockTab);
      });
      win.querySelector('[data-stock-command="new"]').onclick=()=>{
        const active=win.querySelector('[data-stock-tab].active')?.dataset.stockTab;
        if(active==='entry'){
          win.querySelector('[data-stock-panel="entry"]').innerHTML=stockEntryEditor({});
          showTab('entry');
          return;
        }
        if(active==='exit'){
          win.querySelector('[data-stock-panel="exit"]').innerHTML=stockExitEditor({});
          showTab('exit');
          return;
        }
        win.querySelector('[data-stock-panel="card"]').innerHTML=stockCardEditor({});
        showTab('card');
      };
      win.querySelector('[data-stock-command="save"]').onclick=()=>{
        const active=win.querySelector('[data-stock-tab].active')?.dataset.stockTab;
        if(active==='card')saveCard();
        else if(active==='entry')saveMovement('giris');
        else if(active==='exit')saveMovement('cikis');
        else message.textContent='Kaydetmek için Stok Kartı, Stok Giriş veya Stok Çıkış sekmesini açın.';
      };
      win.addEventListener('input',event=>{
        if(event.target.matches('[data-stock-entry-search]')){
          const q=event.target.value.toLocaleLowerCase('tr-TR');
          win.querySelectorAll('tr[data-stock-entry-row]').forEach(row=>row.hidden=!row.dataset.entrySearch.includes(q));
          return;
        }
        if(event.target.matches('[data-stock-exit-search]')){
          const q=event.target.value.toLocaleLowerCase('tr-TR');
          win.querySelectorAll('tr[data-stock-exit-row]').forEach(row=>row.hidden=!row.dataset.exitSearch.includes(q));
          return;
        }
        if(event.target.matches('[data-stock-card-search]')){
          const q=event.target.value.toLocaleLowerCase('tr-TR');
          win.querySelectorAll('tr[data-stock-card-row]').forEach(row=>row.hidden=!row.dataset.cardSearch.includes(q));
          return;
        }
        if(!event.target.matches('[data-stock-search]'))return;
        const q=event.target.value.toLocaleLowerCase('tr-TR'),category=win.querySelector('[data-stock-category]')?.value||'';
        win.querySelectorAll('[data-stock-panel="list"] tr[data-stock-id]').forEach(row=>row.hidden=(!row.dataset.search.includes(q))||(category&&!row.dataset.categoryIds.split(',').includes(category)));
      });
      win.addEventListener('change',event=>{
        if(event.target.matches('[data-stock-category-picker] input[name="category_ids[]"]')){
          updateCategorySummary(event.target.closest('[data-stock-category-picker]'));
          return;
        }
        if(!event.target.matches('[data-stock-category]'))return;
        win.querySelector('[data-stock-search]').dispatchEvent(new Event('input',{bubbles:true}));
      });
      win.addEventListener('click',async event=>{
        if(event.target.closest('[data-stock-entry-action="list"]')){
          showEntryList();
          return;
        }
        if(event.target.closest('[data-stock-exit-action="list"]')){
          showExitList();
          return;
        }
        if(event.target.closest('[data-stock-card-action="list"]')){
          closeCategoryMenus();
          showCardList();
          return;
        }
        const movementAction=event.target.closest('[data-stock-movement-action]');
        if(movementAction){
          const row=movementAction.closest('tr[data-stock-movement-id]'),id=Number(row.dataset.stockMovementId),item=stockMovementRecords.find(record=>Number(record.id)===id);
          if(!item)return;
          const panel=item.type==='giris'?'entry':'exit',label=item.type==='giris'?'giriş':'çıkış';
          if(movementAction.dataset.stockMovementAction==='edit'){
            win.querySelector(`[data-stock-panel="${panel}"]`).innerHTML=item.type==='giris'?stockEntryEditor(item):stockExitEditor(item);
            showTab(panel);
          }
          if(movementAction.dataset.stockMovementAction==='delete'&&confirm(`${item.stock_item?.name||'Bu'} stok ${label} kaydı silinsin mi?`)){
            try{
              await apiFetch(`${endpoints.stockMovements}/${id}`,{method:'DELETE'});
              await load();
              if(item.type==='giris')showEntryList();else showExitList();
              message.textContent=`Stok ${label} kaydı silindi.`;
            }catch(error){
              message.textContent=error.message;
            }
          }
          return;
        }
        const trigger=event.target.closest('[data-stock-category-trigger]');
        if(trigger){
          const picker=trigger.closest('[data-stock-category-picker]'),menu=picker.querySelector('[data-stock-category-menu]'),willOpen=menu.hidden;
          closeCategoryMenus(picker);
          menu.hidden=!willOpen;
          trigger.setAttribute('aria-expanded',String(willOpen));
          return;
        }
        if(!event.target.closest('[data-stock-category-picker]'))closeCategoryMenus();
        const action=event.target.closest('[data-stock-action]');
        if(!action)return;
        const row=action.closest('tr[data-stock-id]'),id=Number(row.dataset.stockId),item=stockRecords.find(record=>record.id===id);
        if(action.dataset.stockAction==='edit'){
          win.querySelector('[data-stock-panel="card"]').innerHTML=stockCardEditor(item);
          showTab('card');
        }
        if(action.dataset.stockAction==='history'){
          showEntryList();
          const search=win.querySelector('[data-stock-entry-search]');
          search.value=item.code;
          search.dispatchEvent(new Event('input',{bubbles:true}));
          message.textContent=`${item.name} stok girişleri listelendi.`;
        }
        if(action.dataset.stockAction==='delete'&&confirm(`${item.name} stok kartı ve tüm hareketleri silinsin mi?`)){
          try{
            await apiFetch(`${endpoints.stockItems}/${id}`,{method:'DELETE'});
            await load();
            showTab('card');
            message.textContent='Stok kartı silindi.';
          }catch(error){
            message.textContent=error.message;
          }
        }
      });
      win.addEventListener('keydown',event=>{
        if(event.key==='Escape')closeCategoryMenus();
      });
      load();
    };
    let cashData={opening_balance:0,income_total:0,expense_total:0,balance:0,transactions:[],categories:[],closings:[]};
    const cashMoney=value=>Number(value||0).toLocaleString('tr-TR',{minimumFractionDigits:2,maximumFractionDigits:2})+' ₺';
    const cashPaymentName=value=>({cash:'Nakit',credit_card:'Kredi Kartı',transfer:'Havale / EFT',room_charge:'Oda Hesabı'}[value]||value);
    const cashTypeName=value=>value==='income'?'Gelir':'Gider';
    const cashCategoryOptions=(type,selected='')=>`<option value="">Kategori seçiniz</option>${cashData.categories.filter(item=>item.active&&item.type===type).map(item=>`<option value="${item.id}" ${Number(selected)===Number(item.id)?'selected':''}>${attr(item.name)}</option>`).join('')}`;
    const cashRows=()=>cashData.transactions.length?cashData.transactions.map(item=>`<tr data-cash-id="${item.id}" data-search="${attr(`${item.description} ${item.category?.name||''} ${item.document_no||''}`.toLocaleLowerCase('tr-TR'))}" data-type="${item.type}"><td>${attr(displayDate(item.transaction_date))}</td><td class="${item.type}">${cashTypeName(item.type)}</td><td>${attr(item.description)}</td><td>${attr(item.category?.name||'—')}</td><td>${cashPaymentName(item.payment_type)}</td><td>${attr(item.document_no||'—')}</td><td class="${item.type}">${item.type==='expense'?'-':'+'}${cashMoney(item.amount)}</td><td><span class="cash-row-actions"><button type="button" data-cash-action="edit" title="Düzenle">✎</button><button type="button" data-cash-action="delete" title="Sil">🗑</button></span></td></tr>`).join(''):'<tr><td colspan="8" class="stock-empty">Henüz kasa hareketi bulunmuyor.</td></tr>';
    const cashTransactionForm=(item={})=>`<form class="cash-form" data-cash-transaction-form data-cash-id="${item.id||''}"><label>İşlem Tarihi *</label><input name="transaction_date" type="date" value="${attr(item.transaction_date||localIso(new Date()))}" required><label>İşlem Türü *</label><select name="type"><option value="income" ${item.type!=='expense'?'selected':''}>Gelir</option><option value="expense" ${item.type==='expense'?'selected':''}>Gider</option></select><label>Açıklama *</label><input name="description" value="${attr(item.description)}" required><label>Tutar *</label><input name="amount" type="number" min="0.01" step="0.01" value="${attr(item.amount)}" required><label>Ödeme Türü *</label><select name="payment_type">${[['cash','Nakit'],['credit_card','Kredi Kartı'],['transfer','Havale / EFT'],['room_charge','Oda Hesabı']].map(([value,label])=>`<option value="${value}" ${item.payment_type===value?'selected':''}>${label}</option>`).join('')}</select><label>Kategori</label><select name="category_id">${cashCategoryOptions(item.type||'income',item.category_id)}</select><label>Belge / Fatura No</label><input name="document_no" value="${attr(item.document_no)}"><div></div></form>`;
    const cashCategoryRows=()=>cashData.categories.length?cashData.categories.map(item=>`<tr data-category-id="${item.id}"><td>${attr(item.name)}</td><td class="${item.type}">${cashTypeName(item.type)}</td><td>${item.active?'Aktif':'Pasif'}</td><td><span class="cash-row-actions"><button type="button" data-category-action="edit" title="Düzenle">✎</button><button type="button" data-category-action="toggle" title="Durumu değiştir">◉</button><button type="button" data-category-action="delete" title="Sil">🗑</button></span></td></tr>`).join(''):'<tr><td colspan="4" class="stock-empty">Kategori bulunmuyor.</td></tr>';
    const cashClosingRows=()=>cashData.closings.length?cashData.closings.map(item=>`<tr><td>${attr(displayDate(item.closing_date))}</td><td>${cashMoney(item.expected_balance)}</td><td>${cashMoney(item.counted_balance)}</td><td class="${Number(item.difference)<0?'expense':'income'}">${cashMoney(item.difference)}</td><td>${attr(item.note||'—')}</td></tr>`).join(''):'<tr><td colspan="5" class="stock-empty">Henüz gün sonu kaydı bulunmuyor.</td></tr>';
    const cashMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="cash-shell"><nav class="cash-tabs"><button type="button" class="active" data-cash-tab="transactions"><span>▤</span>Günlük İşlemler</button><button type="button" data-cash-tab="new"><span>⊕</span>Yeni İşlem</button><button type="button" data-cash-tab="closing"><span>✓</span>Gün Sonu</button><button type="button" data-cash-tab="categories"><span>▦</span>Kategoriler</button></nav><div class="cash-toolbar"><button type="button" data-cash-command="new">⊕ Yeni İşlem</button><button type="button" data-cash-command="save">💾 Kaydet</button><button type="button" data-cash-command="refresh">🔄 Yenile</button><span class="cash-message" role="status"></span></div><section class="cash-panel" data-cash-panel="transactions">Kasa bilgileri yükleniyor…</section><section class="cash-panel" data-cash-panel="new" hidden>${cashTransactionForm()}</section><section class="cash-panel" data-cash-panel="closing" hidden></section><section class="cash-panel" data-cash-panel="categories" hidden></section></div>`;
    const setupCashWindow=win=>{const message=win.querySelector('.cash-message'),showTab=key=>{win.querySelectorAll('[data-cash-tab]').forEach(tab=>tab.classList.toggle('active',tab.dataset.cashTab===key));win.querySelectorAll('[data-cash-panel]').forEach(panel=>panel.hidden=panel.dataset.cashPanel!==key);};const render=()=>{win.querySelector('[data-cash-panel="transactions"]').innerHTML=`<div class="cash-summary"><article><span>DEVREDEN KASA</span><strong>${cashMoney(cashData.opening_balance)}</strong></article><article><span>TOPLAM GELİR</span><strong>${cashMoney(cashData.income_total)}</strong></article><article class="expense"><span>TOPLAM GİDER</span><strong>${cashMoney(cashData.expense_total)}</strong></article><article><span>MEVCUT BAKİYE</span><strong class="${Number(cashData.balance)<0?'negative':''}">${cashMoney(cashData.balance)}</strong></article></div><div class="cash-filters"><label>Devreden Kasa<input type="number" step="0.01" data-opening-balance value="${attr(cashData.opening_balance)}"></label><button type="button" data-cash-action="opening">Devreden Kasayı Kaydet</button><input type="search" data-cash-search placeholder="Açıklama, kategori veya belge no ara"><select data-cash-type-filter><option value="">Tüm işlemler</option><option value="income">Gelir</option><option value="expense">Gider</option></select></div><div class="cash-table-wrap"><table class="cash-table"><thead><tr><th>Tarih</th><th>Tür</th><th>Açıklama</th><th>Kategori</th><th>Ödeme</th><th>Belge No</th><th>Tutar</th><th>İşlemler</th></tr></thead><tbody>${cashRows()}</tbody></table></div>`;win.querySelector('[data-cash-panel="new"]').innerHTML=cashTransactionForm();win.querySelector('[data-cash-panel="closing"]').innerHTML=`<form class="cash-form" data-cash-closing-form><label>Kapanış Tarihi *</label><input name="closing_date" type="date" value="${localIso(new Date())}" required><label>Sistemdeki Bakiye</label><input value="${attr(cashData.balance)}" disabled><label>Sayılan Bakiye *</label><input name="counted_balance" type="number" step="0.01" required><label>Not</label><input name="note" maxlength="255"></form><div class="cash-table-wrap"><table class="cash-table"><thead><tr><th>Tarih</th><th>Beklenen</th><th>Sayılan</th><th>Fark</th><th>Not</th></tr></thead><tbody>${cashClosingRows()}</tbody></table></div>`;win.querySelector('[data-cash-panel="categories"]').innerHTML=`<form class="cash-form" data-cash-category-form data-category-id=""><label>Kategori Adı *</label><input name="name" required><label>Tür *</label><select name="type"><option value="income">Gelir</option><option value="expense">Gider</option></select><label>Durum</label><select name="active"><option value="1">Aktif</option><option value="0">Pasif</option></select><div></div></form><div class="cash-table-wrap"><table class="cash-table"><thead><tr><th>Kategori</th><th>Tür</th><th>Durum</th><th>İşlemler</th></tr></thead><tbody>${cashCategoryRows()}</tbody></table></div>`;};const load=async()=>{message.textContent='Kasa bilgileri yükleniyor…';try{const payload=await apiFetch(endpoints.cash);cashData=payload.data;render();message.textContent=`${cashData.transactions.length} işlem • Bakiye ${cashMoney(cashData.balance)}`;}catch(error){message.textContent=error.message;}};const saveTransaction=async()=>{const form=win.querySelector('[data-cash-panel="new"] [data-cash-transaction-form]');if(!form.reportValidity())return;const values=Object.fromEntries(new FormData(form));values.amount=Number(values.amount);values.category_id=values.category_id?Number(values.category_id):null;const id=form.dataset.cashId;try{await apiFetch(id?`${endpoints.cash}/transactions/${id}`:`${endpoints.cash}/transactions`,{method:id?'PUT':'POST',body:JSON.stringify(values)});await load();showTab('transactions');message.textContent='Kasa işlemi kaydedildi.';}catch(error){message.textContent=error.message;}};const saveClosing=async()=>{const form=win.querySelector('[data-cash-closing-form]');if(!form.reportValidity())return;const values=Object.fromEntries(new FormData(form));values.counted_balance=Number(values.counted_balance);try{await apiFetch(`${endpoints.cash}/closing`,{method:'PUT',body:JSON.stringify(values)});await load();showTab('closing');message.textContent='Gün sonu kaydedildi.';}catch(error){message.textContent=error.message;}};const saveCategory=async()=>{const form=win.querySelector('[data-cash-category-form]');if(!form.reportValidity())return;const values=Object.fromEntries(new FormData(form));values.active=values.active==='1';const id=form.dataset.categoryId;try{await apiFetch(id?`${endpoints.cash}/categories/${id}`:`${endpoints.cash}/categories`,{method:id?'PUT':'POST',body:JSON.stringify(values)});await load();showTab('categories');message.textContent='Kategori kaydedildi.';}catch(error){message.textContent=error.message;}};win.querySelector('.cash-tabs').onclick=event=>{const tab=event.target.closest('[data-cash-tab]');if(tab)showTab(tab.dataset.cashTab);};win.querySelector('[data-cash-command="new"]').onclick=()=>{win.querySelector('[data-cash-panel="new"]').innerHTML=cashTransactionForm();showTab('new');};win.querySelector('[data-cash-command="refresh"]').onclick=load;win.querySelector('[data-cash-command="save"]').onclick=()=>{const active=win.querySelector('[data-cash-tab].active')?.dataset.cashTab;if(active==='new')saveTransaction();else if(active==='closing')saveClosing();else if(active==='categories')saveCategory();else message.textContent='Kaydetmek için Yeni İşlem, Gün Sonu veya Kategoriler sekmesini açın.';};win.addEventListener('change',event=>{if(event.target.matches('[data-cash-transaction-form] [name="type"]')){const form=event.target.closest('form'),selected=form.querySelector('[name="category_id"]').value;form.querySelector('[name="category_id"]').innerHTML=cashCategoryOptions(event.target.value,selected);}if(event.target.matches('[data-cash-type-filter]'))win.querySelector('[data-cash-search]')?.dispatchEvent(new Event('input',{bubbles:true}));});win.addEventListener('input',event=>{if(!event.target.matches('[data-cash-search]'))return;const q=event.target.value.toLocaleLowerCase('tr-TR'),type=win.querySelector('[data-cash-type-filter]').value;win.querySelectorAll('tr[data-cash-id]').forEach(row=>row.hidden=(!row.dataset.search.includes(q))||(type&&row.dataset.type!==type));});win.addEventListener('click',async event=>{const action=event.target.closest('[data-cash-action],[data-category-action]');if(!action)return;if(action.dataset.cashAction==='opening'){try{await apiFetch(`${endpoints.cash}/opening`,{method:'PUT',body:JSON.stringify({opening_balance:Number(win.querySelector('[data-opening-balance]').value||0)})});await load();message.textContent='Devreden kasa güncellendi.';}catch(error){message.textContent=error.message;}return;}const row=action.closest('tr');if(action.dataset.cashAction==='edit'){const item=cashData.transactions.find(record=>record.id===Number(row.dataset.cashId));win.querySelector('[data-cash-panel="new"]').innerHTML=cashTransactionForm(item);showTab('new');}if(action.dataset.cashAction==='delete'&&confirm('Bu kasa hareketi silinsin mi?')){try{await apiFetch(`${endpoints.cash}/transactions/${row.dataset.cashId}`,{method:'DELETE'});await load();message.textContent='Kasa hareketi silindi.';}catch(error){message.textContent=error.message;}}if(action.dataset.categoryAction){const item=cashData.categories.find(record=>record.id===Number(row.dataset.categoryId));if(action.dataset.categoryAction==='edit'){const form=win.querySelector('[data-cash-category-form]');form.dataset.categoryId=item.id;form.elements.name.value=item.name;form.elements.type.value=item.type;form.elements.active.value=item.active?'1':'0';}if(action.dataset.categoryAction==='toggle'){try{await apiFetch(`${endpoints.cash}/categories/${item.id}`,{method:'PUT',body:JSON.stringify({name:item.name,type:item.type,active:!item.active})});await load();showTab('categories');}catch(error){message.textContent=error.message;}}if(action.dataset.categoryAction==='delete'&&confirm('Bu kategori silinsin mi?')){try{await apiFetch(`${endpoints.cash}/categories/${item.id}`,{method:'DELETE'});await load();showTab('categories');}catch(error){message.textContent=error.message;}}}});load();};
    let reservationData={view:'day',anchorDate:localIso(new Date()),month:localIso(new Date()).slice(0,7),reservations:[],members:[],employees:[]};
    const reservationStatusName=value=>({planned:'Planlandı',confirmed:'Onaylandı',completed:'Tamamlandı',cancelled:'İptal',no_show:'Gelmedi'}[value]||value);
    const reservationGuest=item=>item.member?.full_name||item.member?.name||item.guest_name||'';
    const reservationEmployee=item=>item.employee?`${item.employee.first_name} ${item.employee.last_name}`:'Atanmadı';
    const reservationParseDate=value=>{const [year,month,day]=value.split('-').map(Number);return new Date(year,month-1,day);};
    const reservationAddDays=(date,days)=>{const result=new Date(date);result.setDate(result.getDate()+days);return result;};
    const reservationDateLabel=(date,options={day:'2-digit',month:'long',year:'numeric'})=>date.toLocaleDateString('tr-TR',options);
    const reservationRange=()=>{const anchor=reservationParseDate(reservationData.anchorDate);let start,end;if(reservationData.view==='day'){start=anchor;end=reservationAddDays(anchor,1);}else if(reservationData.view==='week'){start=reservationAddDays(anchor,-((anchor.getDay()+6)%7));end=reservationAddDays(start,7);}else{start=new Date(anchor.getFullYear(),anchor.getMonth(),1);end=new Date(anchor.getFullYear(),anchor.getMonth()+1,1);}return{start:localIso(start),end:localIso(end)};};
    const reservationEventsFor=date=>reservationData.reservations.filter(item=>String(item.reservation_date).slice(0,10)===date);
    const reservationEventButton=item=>`<button type="button" class="reservation-event ${item.status}" data-reservation-id="${item.id}" aria-label="${attr(item.service_name)} • ${attr(reservationEmployee(item))}">${attr(String(item.start_time).slice(0,5))} ${attr(reservationGuest(item))}</button>`;
    const reservationHoverMarkup=item=>{const member=item.member||{},directoryMember=reservationData.members.find(record=>Number(record.id)===Number(item.member_id))||{},rows=[['Tarih',displayDate(String(item.reservation_date).slice(0,10))],['Rezervasyon Saati',`${String(item.start_time).slice(0,5)} – ${String(item.end_time).slice(0,5)}`],['Terapist',reservationEmployee(item)],['Misafir',reservationGuest(item)],['Üye No',member.member_no||directoryMember.member_no],['Telefon',formatPhone(item.phone||member.phone||directoryMember.phone)],['Üyelik',member.membership_type],['Doğum Tarihi',member.birth_date?displayDate(String(member.birth_date).slice(0,10)):''],['Durum',reservationStatusName(item.status)],['Not',item.notes]].filter(([,value])=>value);return`<div class="reservation-hover-title">${attr(item.service_name)}</div>${rows.map(([label,value])=>`<div class="reservation-hover-row"><span>${attr(label)}</span><strong>${attr(value)}</strong></div>`).join('')}`;};
    const setupReservationHoverDetails=win=>{let tooltip=null,activeCard=null;const remove=()=>{tooltip?.remove();tooltip=null;activeCard=null;},position=(x,y)=>{if(!tooltip)return;const rect=tooltip.getBoundingClientRect(),left=Math.max(8,Math.min(innerWidth-rect.width-8,x+16)),top=Math.max(8,Math.min(innerHeight-rect.height-8,y+14));tooltip.style.left=left+'px';tooltip.style.top=top+'px';};win.addEventListener('pointerover',event=>{const card=event.target.closest('[data-reservation-id]');if(!card||card===activeCard)return;const item=reservationData.reservations.find(record=>Number(record.id)===Number(card.dataset.reservationId));if(!item)return;remove();activeCard=card;tooltip=document.createElement('div');tooltip.className='reservation-hover-card';tooltip.setAttribute('aria-hidden','true');tooltip.innerHTML=reservationHoverMarkup(item);document.body.appendChild(tooltip);position(event.clientX,event.clientY);});win.addEventListener('pointermove',event=>{if(activeCard&&event.target.closest('[data-reservation-id]')===activeCard)position(event.clientX,event.clientY);});win.addEventListener('pointerout',event=>{const card=event.target.closest('[data-reservation-id]');if(card&&card===activeCard&&!card.contains(event.relatedTarget))remove();});win.addEventListener('scroll',remove,true);win.addEventListener('pointerdown',event=>{if(!event.target.closest('[data-reservation-id]'))remove();});};
    const reservationMemberOptions=selected=>`<option value="">Misafir / Üye değil</option>${reservationData.members.map(item=>`<option value="${item.id}" ${Number(selected)===Number(item.id)?'selected':''}>${attr(item.member_no)} — ${attr(item.name)}</option>`).join('')}`;
    const reservationTherapists=()=>reservationData.employees.filter(item=>String(item.occupation?.name||'').localeCompare('Terapist','tr',{sensitivity:'base'})===0);
    const reservationEmployeeOptions=selected=>{const therapists=reservationTherapists(),selectedEmployee=reservationData.employees.find(item=>Number(item.id)===Number(selected)),options=selectedEmployee&&!therapists.some(item=>Number(item.id)===Number(selectedEmployee.id))?[...therapists,selectedEmployee]:therapists;return`<option value="">Terapist seçiniz</option>${options.map(item=>`<option value="${item.id}" ${Number(selected)===Number(item.id)?'selected':''}>${attr(item.first_name)} ${attr(item.last_name)}</option>`).join('')}`;};
    const reservationServiceTemplates=[
      {id:1,code:'MAS-60',name:'Klasik Masaj 60 dk',duration:60,group:'Masaj'},
      {id:2,code:'ARO-60',name:'Aromaterapi Masajı 60 dk',duration:60,group:'Masaj'},
      {id:3,code:'ARO-90',name:'Aromaterapi Masajı 90 dk',duration:90,group:'Masaj'},
      {id:4,code:'TAS-75',name:'Sıcak Taş Masajı 75 dk',duration:75,group:'Masaj'},
      {id:5,code:'DOR-60',name:'Dört El Masajı 60 dk',duration:60,group:'Masaj'},
      {id:6,code:'HAM-45',name:'Geleneksel Türk Hamamı 45 dk',duration:45,group:'Hamam'},
      {id:7,code:'CIL-60',name:'Klasik Cilt Bakımı 60 dk',duration:60,group:'Bakım'}
    ];
    const reservationForm=(item={},date='',startTime='',endTime='')=>{const selectedMember=reservationData.members.find(member=>Number(member.id)===Number(item.member_id)),selectedService=reservationServiceTemplates.find(service=>service.name===item.service_name),guestName=item.guest_name||selectedMember?.name||'',guestPhone=formatPhone(item.phone||selectedMember?.phone||'');return`<form class="reservation-form" data-reservation-form data-reservation-record-id="${item.id||''}"><div class="reservation-entry-tabs"><button type="button" class="active" data-reservation-form-tab="general">Genel</button><button type="button" data-reservation-form-tab="detail">Detay</button></div><section class="reservation-entry-panel" data-reservation-form-panel="general"><div class="reservation-entry-summary"><div class="reservation-form-field"><label>Tarih *</label><input name="reservation_date" type="date" value="${attr(item.reservation_date||date||localIso(new Date()))}" required></div><div class="reservation-form-field"><label>Başlangıç *</label><input name="start_time" type="time" value="${attr(String(item.start_time||startTime||'09:00').slice(0,5))}" required></div><div class="reservation-form-field"><label>Bitiş *</label><input name="end_time" type="time" value="${attr(String(item.end_time||endTime||'10:00').slice(0,5))}" required></div><div class="reservation-form-field"><label>Terapist</label><select name="employee_id">${reservationEmployeeOptions(item.employee_id)}</select></div></div><div class="reservation-entry-grid"><div class="reservation-entry-box"><div class="reservation-entry-title">Hizmet şablonu seçimi</div><div class="reservation-picker-toolbar"><input type="search" data-reservation-service-search placeholder="Hizmet ara"><select data-reservation-service-group><option value="">Tüm hizmet grupları</option><option>Masaj</option><option>Hamam</option><option>Bakım</option></select></div><div class="reservation-table-scroll"><table class="reservation-template-table"><thead><tr><th>Kod</th><th>Hizmet adı</th><th>Süre (dk.)</th><th>Net süre</th></tr></thead><tbody>${reservationServiceTemplates.map(service=>`<tr data-reservation-service="${service.id}" data-group="${attr(service.group)}" data-search="${attr(`${service.code} ${service.name}`.toLocaleLowerCase('tr-TR'))}" class="${selectedService?.id===service.id?'selected':''}"><td>${attr(service.code)}</td><td>${attr(service.name)}</td><td>${service.duration}</td><td>${service.duration}</td></tr>`).join('')}</tbody></table></div></div><div class="reservation-entry-box"><div class="reservation-entry-title">Seçilen hizmet</div><div class="reservation-selected-service"><strong data-selected-service-name>${attr(item.service_name||'Henüz hizmet seçilmedi')}</strong><span data-selected-service-duration>${selectedService?`${selectedService.duration} dakika`:'Listeden bir hizmet seçin veya aşağıya yazın.'}</span><div class="reservation-form-field"><label>Hizmet adı *</label><input name="service_name" value="${attr(item.service_name)}" placeholder="Hizmet seçiniz" required></div></div><div class="reservation-entry-title" style="margin-top:9px">Ek hizmetler ve ürünler</div><div class="reservation-extra-tools"><button type="button" title="Ek hizmet ekle">＋</button><button type="button" title="Seçileni kaldır">×</button></div><table class="reservation-extra-table"><thead><tr><th>Ürün / Hizmet</th><th>Fiyat</th></tr></thead><tbody><tr><td colspan="2" class="reservation-extra-empty">Ek hizmet bulunmuyor.</td></tr></tbody></table></div></div><div class="reservation-entry-box"><div class="reservation-entry-title">Üye / misafir seçimi</div><div class="reservation-guest-section"><div><input class="reservation-guest-search" type="search" data-reservation-guest-search placeholder="Üye no veya ad soyad ara"><div class="reservation-table-scroll" style="height:180px"><table class="reservation-guest-table"><thead><tr><th>Üye No</th><th>Ad Soyad</th><th>Telefon</th></tr></thead><tbody>${reservationData.members.map(member=>`<tr data-reservation-member="${member.id}" data-search="${attr(`${member.member_no} ${member.name} ${member.phone||''}`.toLocaleLowerCase('tr-TR'))}" class="${Number(item.member_id)===Number(member.id)?'selected':''}"><td>${attr(member.member_no)}</td><td>${attr(member.name)}</td><td>${attr(formatPhone(member.phone)||'—')}</td></tr>`).join('')}</tbody></table></div></div><div class="reservation-selected-guest"><strong data-selected-guest-name>${attr(guestName||'Misafir bilgileri')}</strong><span data-selected-guest-phone>${attr(guestPhone||'Telefon bilgisi yok')}</span><div class="reservation-form-field" style="margin-top:12px"><label>Ad Soyad *</label><input name="guest_name" value="${attr(guestName)}" required></div><select name="member_id" hidden>${reservationMemberOptions(item.member_id)}</select></div></div></div></section><section class="reservation-entry-panel reservation-detail-grid" data-reservation-form-panel="detail" hidden><label>Telefon</label><input name="phone" type="tel" inputmode="numeric" maxlength="17" data-phone-input placeholder="0 (XXX) XXX XX XX" value="${attr(guestPhone)}"><label>Durum</label><select name="status">${[['planned','Planlandı'],['confirmed','Onaylandı'],['completed','Tamamlandı'],['cancelled','İptal'],['no_show','Gelmedi']].map(([value,label])=>`<option value="${value}" ${item.status===value||(!item.status&&value==='planned')?'selected':''}>${label}</option>`).join('')}</select><label>Not</label><textarea name="notes">${attr(item.notes)}</textarea></section></form>`;};
    const reservationEntryScreen=(item={},date='',startTime='',endTime='')=>`<section class="reservation-entry-screen" data-reservation-entry-screen><div class="reservation-entry-titlebar"><span>Rezervasyon Girişi</span><div class="window-controls"><button type="button" class="reservation-entry-close" data-reservation-entry-close aria-label="Rezervasyon girişini kapat" title="Takvime dön">×</button></div></div><div class="reservation-entry-toolbar"><button type="button" data-reservation-entry-save>💾 Kaydet</button><span class="reservation-entry-message" data-reservation-entry-message role="status"></span></div><div class="reservation-entry-body">${reservationForm(item,date,startTime,endTime)}</div></section>`;
    const reservationPeriodHeader=title=>`<div class="reservation-month-head"><button type="button" data-reservation-nav="-1" title="Önceki">‹</button><strong>${attr(title)}</strong><button type="button" data-reservation-nav="1" title="Sonraki">›</button></div>`;
    const reservationMonthMarkup=()=>{const anchor=reservationParseDate(reservationData.anchorDate),year=anchor.getFullYear(),month=anchor.getMonth(),first=new Date(year,month,1),last=new Date(year,month+1,0),leading=(first.getDay()+6)%7,today=localIso(new Date());let days='';for(let i=0;i<leading;i++)days+='<div class="reservation-day muted"></div>';for(let day=1;day<=last.getDate();day++){const date=localIso(new Date(year,month,day));days+=`<div class="reservation-day ${date===today?'today':''}" data-reservation-date="${date}"><div class="reservation-day-head"><span>${day}</span><button type="button" data-reservation-add="${date}" title="Rezervasyon ekle">＋</button></div>${reservationEventsFor(date).map(reservationEventButton).join('')}</div>`;}return`${reservationPeriodHeader(reservationDateLabel(first,{month:'long',year:'numeric'}))}<div class="reservation-calendar">${['Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi','Pazar'].map(day=>`<div class="reservation-weekday">${day}</div>`).join('')}${days}</div>`;};
    const reservationWeekMarkup=()=>{const range=reservationRange(),start=reservationParseDate(range.start),end=reservationAddDays(reservationParseDate(range.end),-1),today=localIso(new Date()),columns=Array.from({length:7},(_,index)=>{const current=reservationAddDays(start,index),date=localIso(current);return`<div class="reservation-week-column ${date===today?'today':''}" data-reservation-date="${date}"><div class="reservation-weekday">${reservationDateLabel(current,{weekday:'long'})}</div><div class="reservation-day-head"><span>${reservationDateLabel(current,{day:'2-digit',month:'2-digit'})}</span><button type="button" data-reservation-add="${date}" title="Rezervasyon ekle">＋</button></div>${reservationEventsFor(date).map(reservationEventButton).join('')}</div>`;}).join('');return`${reservationPeriodHeader(`${reservationDateLabel(start,{day:'2-digit',month:'long'})} – ${reservationDateLabel(end,{day:'2-digit',month:'long',year:'numeric'})}`)}<div class="reservation-week-calendar">${columns}</div>`;};
    const reservationDayMarkup=()=>{
      const anchor=reservationParseDate(reservationData.anchorDate),date=localIso(anchor),therapists=reservationTherapists(),therapistIds=new Set(therapists.map(item=>Number(item.id))),items=reservationEventsFor(date).filter(item=>therapistIds.has(Number(item.employee_id))).slice().sort((a,b)=>String(a.start_time).localeCompare(String(b.start_time))),opening=8*60,closing=22*60,toMinutes=value=>{const [hour,minute]=String(value).slice(0,5).split(':').map(Number);return hour*60+minute;},labels=Array.from({length:15},(_,index)=>{const minute=opening+index*60,top=minute-opening;return`<span class="reservation-time-label" style="top:${top}px">${String(Math.floor(minute/60)).padStart(2,'0')}:00</span>`;}).join(''),lines=Array.from({length:29},(_,index)=>`<span class="reservation-time-line ${index%2?'half':''}" style="top:${index*30}px"></span>`).join(''),eventMarkup=item=>{const start=toMinutes(item.start_time),end=toMinutes(item.end_time),top=Math.max(start,opening)-opening,height=Math.min(end,closing)-Math.max(start,opening);return end>opening&&start<closing?`<button type="button" draggable="true" class="reservation-day-event ${item.status}" data-reservation-id="${item.id}" style="top:${top}px;height:${height}px;left:6px;width:calc(100% - 12px)" aria-label="${attr(item.service_name)} • ${attr(reservationGuest(item))} • ${attr(reservationEmployee(item))}"><strong>${attr(String(item.start_time).slice(0,5))}–${attr(String(item.end_time).slice(0,5))} • ${attr(reservationGuest(item))}</strong><span>${attr(item.service_name)}</span><span>${attr(reservationStatusName(item.status))}</span></button>`:'';};
      const grid=therapists.length?`<div class="reservation-therapist-grid" style="--therapist-count:${therapists.length}"><div class="reservation-therapist-corner"></div>${therapists.map(therapist=>`<div class="reservation-therapist-head"><span>${attr(therapist.first_name)} ${attr(therapist.last_name)}</span><small>Terapist</small></div>`).join('')}<div class="reservation-time-axis">${labels}</div>${therapists.map(therapist=>{const events=items.filter(item=>Number(item.employee_id)===Number(therapist.id)).map(eventMarkup).join('');return`<div class="reservation-time-canvas reservation-therapist-canvas" data-reservation-date="${date}" data-employee-id="${therapist.id}" aria-label="${attr(therapist.first_name)} ${attr(therapist.last_name)} rezervasyon çizelgesi">${lines}${events}</div>`;}).join('')}</div>`:'<div class="reservation-no-therapist">Aktif personeller arasında mesleği “Terapist” olarak tanımlanmış çalışan bulunamadı.<br>Personel kartından meslek ataması yapabilirsiniz.</div>';
      return`${reservationPeriodHeader(reservationDateLabel(anchor,{weekday:'long',day:'2-digit',month:'long',year:'numeric'}))}<div class="reservation-day-view"><div class="reservation-timeline">${grid}</div></div>`;
    };
    const reservationCalendarMarkup=()=>reservationData.view==='day'?reservationDayMarkup():reservationData.view==='week'?reservationWeekMarkup():reservationMonthMarkup();
    const setupReservationDragDrop=win=>{
      let draggedId=null,dragOffsetMinutes=0,ignoreClickUntil=0;
      const opening=8*60,closing=22*60,message=win.querySelector('.reservation-message'),toMinutes=value=>{const [hour,minute]=String(value).slice(0,5).split(':').map(Number);return hour*60+minute;},formatTime=value=>`${String(Math.floor(value/60)).padStart(2,'0')}:${String(value%60).padStart(2,'0')}`,clear=()=>{win.querySelectorAll('.reservation-day-event.dragging').forEach(card=>card.classList.remove('dragging'));win.querySelectorAll('.reservation-therapist-canvas.drag-over').forEach(canvas=>canvas.classList.remove('drag-over'));},slotForDrop=(event,canvas,duration)=>{const rect=canvas.getBoundingClientRect(),pointer=opening+((event.clientY-rect.top)/rect.height)*(closing-opening),rounded=Math.round((pointer-dragOffsetMinutes)/30)*30;return Math.max(opening,Math.min(closing-duration,rounded));};
      win.addEventListener('dragstart',event=>{const card=event.target.closest('.reservation-day-event[data-reservation-id]');if(!card)return;const item=reservationData.reservations.find(record=>Number(record.id)===Number(card.dataset.reservationId));if(!item)return;const cardRect=card.getBoundingClientRect(),duration=Math.max(30,toMinutes(item.end_time)-toMinutes(item.start_time));draggedId=Number(card.dataset.reservationId);dragOffsetMinutes=Math.max(0,Math.min(duration,((event.clientY-cardRect.top)/cardRect.height)*duration));card.classList.add('dragging');event.dataTransfer.effectAllowed='move';event.dataTransfer.setData('text/plain',String(draggedId));message.textContent='Rezervasyonu yeni saatine veya terapistine bırakın.';});
      win.addEventListener('dragover',event=>{const canvas=event.target.closest('.reservation-therapist-canvas');if(!canvas||!draggedId)return;event.preventDefault();event.dataTransfer.dropEffect='move';win.querySelectorAll('.reservation-therapist-canvas.drag-over').forEach(item=>item.classList.toggle('drag-over',item===canvas));});
      win.addEventListener('dragleave',event=>{const canvas=event.target.closest('.reservation-therapist-canvas');if(canvas&&!canvas.contains(event.relatedTarget))canvas.classList.remove('drag-over');});
      win.addEventListener('drop',async event=>{const canvas=event.target.closest('.reservation-therapist-canvas');if(!canvas||!draggedId)return;event.preventDefault();event.stopPropagation();const item=reservationData.reservations.find(record=>Number(record.id)===draggedId),employeeId=Number(canvas.dataset.employeeId);clear();ignoreClickUntil=Date.now()+350;if(!item)return;const oldStart=toMinutes(item.start_time),duration=Math.max(30,toMinutes(item.end_time)-oldStart),newStart=slotForDrop(event,canvas,duration),newEnd=newStart+duration;if(Number(item.employee_id)===employeeId&&oldStart===newStart){message.textContent='Rezervasyon aynı saat ve terapistte kaldı.';draggedId=null;return;}const values={member_id:item.member_id?Number(item.member_id):null,employee_id:employeeId,guest_name:item.guest_name||reservationGuest(item),phone:item.phone||null,service_name:item.service_name,reservation_date:String(item.reservation_date).slice(0,10),start_time:formatTime(newStart),end_time:formatTime(newEnd),status:item.status,notes:item.notes||null};message.textContent='Rezervasyon taşınıyor…';try{const payload=await apiFetch(`${endpoints.reservations}/${item.id}`,{method:'PUT',body:JSON.stringify(values)});reservationData.reservations=reservationData.reservations.map(record=>Number(record.id)===Number(item.id)?payload.data:record);win.querySelector('[data-reservation-panel="calendar"]').innerHTML=reservationCalendarMarkup();win.querySelector('[data-reservation-panel="list"]').innerHTML=reservationListMarkup();message.textContent=`Rezervasyon ${reservationEmployee(payload.data)} • ${formatTime(newStart)}–${formatTime(newEnd)} aralığına taşındı.`;}catch(error){message.textContent=error.message;}finally{draggedId=null;clear();}});
      win.addEventListener('dragend',()=>{draggedId=null;dragOffsetMinutes=0;clear();});
      win.addEventListener('click',event=>{if(Date.now()<ignoreClickUntil&&event.target.closest('[data-reservation-id]')){event.preventDefault();event.stopImmediatePropagation();}},true);
    };
    const setupReservationMemberPhone=win=>win.addEventListener('click',event=>{const form=event.target.closest('[data-reservation-form]'),memberRow=event.target.closest('[data-reservation-member]');if(!form||!memberRow)return;const member=reservationData.members.find(item=>item.id===Number(memberRow.dataset.reservationMember));if(member)form.elements.phone.value=formatPhone(member.phone);});
    const setupReservationEntryInteractions=win=>{win.dataset.reservationEntryReady='1';const formatTime=minutes=>`${String(Math.floor(minutes/60)%24).padStart(2,'0')}:${String(minutes%60).padStart(2,'0')}`,filterServices=form=>{const query=form.querySelector('[data-reservation-service-search]')?.value.toLocaleLowerCase('tr-TR')||'',group=form.querySelector('[data-reservation-service-group]')?.value||'';form.querySelectorAll('[data-reservation-service]').forEach(row=>row.hidden=(!row.dataset.search.includes(query))||(group&&row.dataset.group!==group));};win.addEventListener('click',event=>{const form=event.target.closest('[data-reservation-form]')||win.querySelector('[data-reservation-form]'),tab=event.target.closest('[data-reservation-form-tab]');if(tab&&form){form.querySelectorAll('[data-reservation-form-tab]').forEach(button=>button.classList.toggle('active',button===tab));form.querySelectorAll('[data-reservation-form-panel]').forEach(panel=>panel.hidden=panel.dataset.reservationFormPanel!==tab.dataset.reservationFormTab);return;}const serviceRow=event.target.closest('[data-reservation-service]');if(serviceRow&&form){const service=reservationServiceTemplates.find(item=>item.id===Number(serviceRow.dataset.reservationService));if(!service)return;form.querySelectorAll('[data-reservation-service]').forEach(row=>row.classList.toggle('selected',row===serviceRow));form.elements.service_name.value=service.name;form.querySelector('[data-selected-service-name]').textContent=service.name;form.querySelector('[data-selected-service-duration]').textContent=`${service.duration} dakika`;const [hour,minute]=form.elements.start_time.value.split(':').map(Number);form.elements.end_time.value=formatTime(hour*60+minute+service.duration);return;}const memberRow=event.target.closest('[data-reservation-member]');if(memberRow&&form){const member=reservationData.members.find(item=>item.id===Number(memberRow.dataset.reservationMember));if(!member)return;form.querySelectorAll('[data-reservation-member]').forEach(row=>row.classList.toggle('selected',row===memberRow));form.elements.member_id.value=member.id;form.elements.member_id.dispatchEvent(new Event('change',{bubbles:true}));form.querySelector('[data-selected-guest-name]').textContent=member.name;form.querySelector('[data-selected-guest-phone]').textContent=formatPhone(member.phone)||'Telefon bilgisi yok';}});win.addEventListener('input',event=>{const form=event.target.closest('[data-reservation-form]');if(!form)return;if(event.target.matches('[data-reservation-service-search]'))filterServices(form);if(event.target.matches('[data-reservation-guest-search]')){const query=event.target.value.toLocaleLowerCase('tr-TR');form.querySelectorAll('[data-reservation-member]').forEach(row=>row.hidden=!row.dataset.search.includes(query));}});win.addEventListener('change',event=>{const form=event.target.closest('[data-reservation-form]');if(form&&event.target.matches('[data-reservation-service-group]'))filterServices(form);});};
    const setupReservationContextMenu=win=>{const removeMenu=()=>win.querySelector('.reservation-context-menu')?.remove(),formatTime=value=>`${String(Math.floor(value/60)).padStart(2,'0')}:${String(value%60).padStart(2,'0')}`;win.addEventListener('contextmenu',event=>{const reservationButton=event.target.closest('[data-reservation-id]'),canvas=event.target.closest('.reservation-time-canvas'),day=event.target.closest('[data-reservation-date]');if(!reservationButton&&!canvas&&!day)return;event.preventDefault();event.stopImmediatePropagation();removeMenu();const item=reservationButton?reservationData.reservations.find(record=>record.id===Number(reservationButton.dataset.reservationId)):null,date=item?String(item.reservation_date).slice(0,10):(canvas?.dataset.reservationDate||day?.dataset.reservationDate||reservationData.anchorDate),employeeId=item?.employee_id||canvas?.dataset.employeeId||'';let startTime='',endTime='';if(canvas&&!item){const rect=canvas.getBoundingClientRect(),minutes=Math.min(21*60+30,Math.max(8*60,Math.round((8*60+((event.clientY-rect.top)/rect.height)*(14*60))/30)*30));startTime=formatTime(minutes);endTime=formatTime(minutes+30);}const menu=document.createElement('div');menu.className='reservation-context-menu';menu.style.left=Math.max(4,Math.min(innerWidth-150,event.clientX))+'px';menu.style.top=Math.max(4,Math.min(innerHeight-112,event.clientY))+'px';menu.innerHTML=`<button type="button" data-reservation-context="new">⊕ Yeni</button><hr><button type="button" data-reservation-context="edit" ${item?'':'disabled'}>✎ Düzenle</button><button type="button" data-reservation-context="delete" ${item?'':'disabled'}>🗑 Sil</button>`;win.appendChild(menu);menu.onclick=async clickEvent=>{const action=clickEvent.target.closest('[data-reservation-context]')?.dataset.reservationContext;if(!action)return;removeMenu();if(action==='new'){win.querySelector('[data-reservation-command="new"]').click();const form=win.querySelector('[data-reservation-form]');form.elements.reservation_date.value=date;if(employeeId)form.elements.employee_id.value=employeeId;if(startTime){form.elements.start_time.value=startTime;form.elements.end_time.value=endTime;}}if(action==='edit'&&item)reservationButton.click();if(action==='delete'&&item&&confirm('Bu rezervasyon silinsin mi?')){const message=win.querySelector('.reservation-message');try{await apiFetch(`${endpoints.reservations}/${item.id}`,{method:'DELETE'});win.querySelector('[data-reservation-command="refresh"]').click();message.textContent='Rezervasyon silindi.';}catch(error){message.textContent=error.message;}}};},true);win.addEventListener('pointerdown',event=>{if(!event.target.closest('.reservation-context-menu'))removeMenu();},true);};
    const setupReservationDoubleClick=win=>{const formatTime=value=>`${String(Math.floor(value/60)).padStart(2,'0')}:${String(value%60).padStart(2,'0')}`;win.addEventListener('dblclick',event=>{if(event.target.closest('[data-reservation-id]'))return;const canvas=event.target.closest('.reservation-therapist-canvas');if(!canvas)return;event.preventDefault();event.stopImmediatePropagation();const rect=canvas.getBoundingClientRect(),minutes=Math.min(21*60+30,Math.max(8*60,Math.round((8*60+((event.clientY-rect.top)/rect.height)*(14*60))/30)*30));win.querySelector('[data-reservation-command="new"]').click();const form=win.querySelector('[data-reservation-form]');form.elements.reservation_date.value=canvas.dataset.reservationDate||reservationData.anchorDate;form.elements.start_time.value=formatTime(minutes);form.elements.end_time.value=formatTime(minutes+30);if(canvas.dataset.employeeId)form.elements.employee_id.value=canvas.dataset.employeeId;},true);};
    const reservationRows=()=>reservationData.reservations.length?reservationData.reservations.map(item=>`<tr data-reservation-row="${item.id}" data-search="${attr(`${reservationGuest(item)} ${item.phone||''} ${item.service_name} ${reservationEmployee(item)}`.toLocaleLowerCase('tr-TR'))}" data-status="${item.status}"><td>${attr(displayDate(String(item.reservation_date).slice(0,10)))}</td><td>${attr(String(item.start_time).slice(0,5))}–${attr(String(item.end_time).slice(0,5))}</td><td>${attr(reservationGuest(item))}</td><td>${attr(formatPhone(item.phone)||'—')}</td><td>${attr(item.service_name)}</td><td>${attr(reservationEmployee(item))}</td><td>${reservationStatusName(item.status)}</td><td><span class="reservation-actions"><button type="button" data-reservation-action="edit">✎</button><button type="button" data-reservation-action="delete">🗑</button></span></td></tr>`).join(''):'<tr><td colspan="8" class="stock-empty">Seçili dönem için rezervasyon bulunmuyor.</td></tr>';
    const reservationListMarkup=()=>`<div class="reservation-filters"><input type="search" data-reservation-search placeholder="Üye, telefon, hizmet veya personel ara"><select data-reservation-status><option value="">Tüm durumlar</option><option value="planned">Planlandı</option><option value="confirmed">Onaylandı</option><option value="completed">Tamamlandı</option><option value="cancelled">İptal</option><option value="no_show">Gelmedi</option></select></div><div class="reservation-table-wrap"><table class="reservation-table"><thead><tr><th>Tarih</th><th>Saat</th><th>Ad Soyad</th><th>Telefon</th><th>Hizmet</th><th>Personel</th><th>Durum</th><th>İşlemler</th></tr></thead><tbody>${reservationRows()}</tbody></table></div>`;
    const reservationMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="reservation-shell"><nav class="reservation-tabs" hidden><button type="button" class="active" data-reservation-tab="calendar"><span>▦</span>Takvim</button><button type="button" data-reservation-tab="form"><span>⊕</span>Yeni Rezervasyon</button><button type="button" data-reservation-tab="list"><span>▤</span>Rezervasyon Listesi</button></nav><div class="reservation-toolbar"><button type="button" data-reservation-command="new">⊕ Yeni</button><button type="button" data-reservation-command="save">💾 Kaydet</button><button type="button" data-reservation-command="refresh" hidden>🔄 Yenile</button><span class="reservation-message" role="status"></span></div><section class="reservation-panel" data-reservation-panel="calendar">Takvim yükleniyor…</section><section class="reservation-panel" data-reservation-panel="form" hidden></section><section class="reservation-panel" data-reservation-panel="list" hidden>${reservationListMarkup()}</section></div>`;
    let packageRecords=[];
    const packageActionIcon=type=>type==='edit'?'<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3 11.8 2.5 14l2.2-.5 7.6-7.6-1.7-1.7zM9.8 5l1.7 1.7 1.5-1.5a1.2 1.2 0 0 0 0-1.7l-.1-.1a1.2 1.2 0 0 0-1.7 0z"/><path d="M8.5 3H4a2 2 0 0 0-2 2v6"/></svg>':'<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3.5 4.5h9M6 2.5h4l.7 2H5.3zM4.7 4.5l.6 9h5.4l.6-9M7 7v4M9 7v4"/></svg>';
    const packageRows=()=>packageRecords.length?packageRecords.map(item=>`<tr data-package-row="${item.id}"><td>${attr(item.name)}</td><td>${attr(item.duration_text)}</td><td>${attr(item.featured_contents)}</td><td>${attr(item.target_audience)}</td><td><span class="package-row-actions"><button type="button" data-package-action="edit" title="Düzenle" aria-label="${attr(item.name)} paketini düzenle">${packageActionIcon('edit')}</button><button type="button" data-package-action="delete" title="Sil" aria-label="${attr(item.name)} paketini sil">${packageActionIcon('delete')}</button></span></td></tr>`).join(''):'<tr><td colspan="5" class="stock-empty">Henüz paket tanımlanmamış.</td></tr>';
    const packageForm=(item={})=>`<form class="packages-form" data-package-form data-package-id="${item.id||''}"><label>Paket Türü *</label><input name="name" maxlength="190" value="${attr(item.name)}" required><label>Ortalama Süre *</label><input name="duration_text" maxlength="100" value="${attr(item.duration_text)}" placeholder="Örn. 90 - 120 Dakika" required><label>Öne Çıkan İçerikler *</label><textarea name="featured_contents" maxlength="1000" required>${attr(item.featured_contents)}</textarea><label>Kimler İçin Uygun? *</label><textarea name="target_audience" maxlength="1000" required>${attr(item.target_audience)}</textarea><div class="packages-form-actions"><button type="submit">💾 Kaydet</button><button type="button" data-package-action="cancel">× İptal</button></div></form>`;
    const packagesMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="packages-shell"><div class="packages-toolbar"><button type="button" data-package-action="new">⊕ Yeni Paket</button><button type="button" data-package-action="refresh">🔄 Yenile</button><span class="packages-message" role="status"></span></div><div class="packages-body"><section class="packages-editor" data-package-editor hidden></section><p class="packages-intro">Sofitel Spa paketleri ve öne çıkan içerikleri</p><div class="packages-table-wrap"><table class="packages-table"><thead><tr><th>Paket Türü</th><th>Ortalama Süre</th><th>Öne Çıkan İçerikler</th><th>Kimler İçin Uygun?</th><th>İşlemler</th></tr></thead><tbody><tr><td colspan="5" class="stock-empty">Paketler yükleniyor…</td></tr></tbody></table></div></div></div>`;
    const setupPackageWindow=win=>{const message=win.querySelector('.packages-message'),editor=win.querySelector('[data-package-editor]'),tbody=win.querySelector('.packages-table tbody'),render=()=>tbody.innerHTML=packageRows(),openEditor=item=>{editor.innerHTML=packageForm(item);editor.hidden=false;editor.querySelector('input')?.focus();},closeEditor=()=>{editor.hidden=true;editor.innerHTML='';},load=async()=>{message.textContent='Paketler yükleniyor…';try{const payload=await apiFetch(endpoints.packages);packageRecords=payload.data;render();message.textContent=`${packageRecords.length} paket`;}catch(error){message.textContent=error.message;}};win.addEventListener('submit',async event=>{const form=event.target.closest('[data-package-form]');if(!form)return;event.preventDefault();if(!form.reportValidity())return;const values=Object.fromEntries(new FormData(form)),id=form.dataset.packageId;message.textContent=id?'Paket güncelleniyor…':'Paket kaydediliyor…';try{await apiFetch(id?`${endpoints.packages}/${id}`:endpoints.packages,{method:id?'PUT':'POST',body:JSON.stringify(values)});await load();closeEditor();message.textContent=id?'Paket güncellendi.':'Paket eklendi.';}catch(error){message.textContent=error.message;}});win.addEventListener('click',async event=>{const action=event.target.closest('[data-package-action]');if(!action)return;const type=action.dataset.packageAction;if(type==='new')openEditor({});if(type==='refresh')await load();if(type==='cancel')closeEditor();const row=action.closest('tr[data-package-row]');if(!row)return;const item=packageRecords.find(record=>Number(record.id)===Number(row.dataset.packageRow));if(type==='edit')openEditor(item);if(type==='delete'&&confirm(`${item.name} silinsin mi?`)){message.textContent='Paket siliniyor…';try{await apiFetch(`${endpoints.packages}/${item.id}`,{method:'DELETE'});await load();closeEditor();message.textContent='Paket silindi.';}catch(error){message.textContent=error.message;}}});load();};
    let currentAccountRecords=[];
    const currentAccountType=value=>({tedarikci:'Tedarikçi',musteri:'Müşteri',diger:'Diğer'})[value]||value||'—';
    const currentMoney=value=>new Intl.NumberFormat('tr-TR',{minimumFractionDigits:2,maximumFractionDigits:2}).format(Number(value||0))+' TL';
    const currentQuantity=value=>new Intl.NumberFormat('tr-TR',{maximumFractionDigits:2}).format(Number(value||0));
    const currentActionIcon=type=>({
      movements:'<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3.2 5.2A5.5 5.5 0 1 1 2.7 10"/><path d="M3.2 2.5v2.7H.5"/><path d="M8 4.5V8l2.4 1.5"/></svg>',
      edit:'<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3 11.8 2.5 14l2.2-.5 7.6-7.6-1.7-1.7zM9.8 5l1.7 1.7 1.5-1.5a1.2 1.2 0 0 0 0-1.7l-.1-.1a1.2 1.2 0 0 0-1.7 0z"/><path d="M8.5 3H4a2 2 0 0 0-2 2v6"/></svg>',
      delete:'<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M3.5 4.5h9M6 2.5h4l.7 2H5.3zM4.7 4.5l.6 9h5.4l.6-9M7 7v4M9 7v4"/></svg>'
    })[type];
    const currentRows=()=>currentAccountRecords.length?currentAccountRecords.map(item=>`<tr data-current-id="${item.id}" data-search="${attr(`${item.code} ${item.short_name} ${item.title} ${item.authorized_person||''} ${item.phone||''}`.toLocaleLowerCase('tr-TR'))}"><td>${attr(item.code)}</td><td>${attr(item.short_name)}</td><td>${attr(item.authorized_person||'—')}</td><td>${attr(currentAccountType(item.type))}</td><td>${attr(formatPhone(item.phone)||'—')}</td><td><span class="current-actions"><button type="button" data-current-action="movements" title="Fatura hareketleri" aria-label="${attr(item.short_name)} fatura hareketleri">${currentActionIcon('movements')}</button><button type="button" data-current-action="edit" title="Düzenle" aria-label="${attr(item.short_name)} cari kartını düzenle">${currentActionIcon('edit')}</button><button type="button" data-current-action="delete" title="Sil" aria-label="${attr(item.short_name)} cari kartını sil">${currentActionIcon('delete')}</button></span></td></tr>`).join(''):'<tr><td colspan="6" class="current-empty">Henüz cari kart bulunmuyor.</td></tr>';
    const currentListMarkup=()=>`<div class="current-list-toolbar" data-current-toolbar><button type="button" class="current-new" data-current-action="new">Yeni Cari Kart&nbsp; ＋</button><span class="current-message" role="status">${currentAccountRecords.length} cari kart</span></div><div class="current-body"><input class="current-search" type="search" data-current-search placeholder="Kod, kısa ad, unvan, ilgili kişi veya telefon ara"><div class="current-table-wrap"><table class="current-table"><thead><tr><th>Kod</th><th>Kısa Ad</th><th>İlgili Kişi</th><th>Tip</th><th>Telefon</th><th>İşlemler</th></tr></thead><tbody>${currentRows()}</tbody></table></div></div>`;
    const currentFormMarkup=(item={})=>`<div class="current-card-toolbar" data-current-toolbar><button type="button" data-current-action="back">← Listeye Dön</button><span class="current-message" role="status"></span></div><form data-current-form data-current-id="${item.id||''}"><div class="current-card-heading">${item.id?'Cari Kartı Düzenle':'Yeni Cari Kart'} <span>＋</span></div><fieldset class="current-fieldset"><legend>Temel ve Kimlik Bilgileri</legend><div class="current-form-grid"><div class="current-form-field"><label>Cari Kodu *</label><input name="code" maxlength="40" value="${attr(item.code)}" required></div><div class="current-form-field"><label>Cari Unvanı / Adı *</label><input name="title" maxlength="190" value="${attr(item.title)}" required></div><div class="current-form-field"><label>Kısa Ad *</label><input name="short_name" maxlength="120" value="${attr(item.short_name)}" required></div><div class="current-form-field"><label>Vergi Dairesi</label><input name="tax_office" maxlength="120" value="${attr(item.tax_office)}"></div><div class="current-form-field"><label>Vergi No / T.C. Kimlik No</label><input name="tax_number" maxlength="20" value="${attr(item.tax_number)}"></div><div class="current-form-field"><label>Cari Tipi *</label><select name="type" required><option value="tedarikci" ${item.type==='tedarikci'||!item.type?'selected':''}>Tedarikçi</option><option value="musteri" ${item.type==='musteri'?'selected':''}>Müşteri</option><option value="diger" ${item.type==='diger'?'selected':''}>Diğer</option></select></div></div></fieldset><fieldset class="current-fieldset"><legend>İletişim ve Adres Bilgileri</legend><div class="current-form-grid"><div class="current-form-field"><label>Telefon</label><input name="phone" type="tel" inputmode="numeric" maxlength="17" data-phone-input placeholder="0 (XXX) XXX XX XX" value="${attr(formatPhone(item.phone))}"></div><div class="current-form-field"><label>E-posta</label><input type="email" name="email" maxlength="190" value="${attr(item.email)}"></div><div class="current-form-field"><label>Durum</label><select name="status"><option value="aktif" ${item.status!=='pasif'?'selected':''}>Aktif</option><option value="pasif" ${item.status==='pasif'?'selected':''}>Pasif</option></select></div><div class="current-form-field"><label>Yetkili Kişi</label><input name="authorized_person" maxlength="150" value="${attr(item.authorized_person)}"></div><div class="current-form-field current-wide" style="grid-column:2/-1"><label>Fatura Adresi</label><textarea name="invoice_address" maxlength="2000">${attr(item.invoice_address)}</textarea></div><div class="current-form-field current-wide" style="grid-column:2/-1"><label>Firma Detayı</label><textarea name="company_detail" maxlength="2000">${attr(item.company_detail)}</textarea></div></div></fieldset><div class="current-card-footer"><button type="submit">▣ Kaydet (F2)</button></div></form>`;
    const currentItemRows=invoice=>(invoice.items||[]).length?invoice.items.map(line=>`<tr><td>${attr(line.stock_item?`${line.stock_item.code} — ${line.stock_item.name}`:line.description)}</td><td>${currentQuantity(line.quantity)}</td><td>${currentQuantity(line.discount_rate)}%</td><td>${currentMoney(line.unit_price)}</td></tr>`).join(''):'<tr><td colspan="4" class="current-empty">Bu faturaya ait satır detayı bulunmuyor.</td></tr>';
    const currentLedgerRows=(account,expanded)=>account.invoices?.length?account.invoices.map(invoice=>`<tr><td>${attr(displayDate(String(invoice.movement_date).slice(0,10)))}</td><td>${invoice.movement_type==='giris'?'Giriş':'Çıkış'}</td><td>${attr(invoice.invoice_no||'—')}</td><td>${currentQuantity(invoice.quantity)}</td><td>${currentMoney(invoice.total_amount)}</td><td>${currentMoney(invoice.total_with_tax)}</td><td>${currentMoney(invoice.undiscounted_amount)}</td><td>${currentMoney(invoice.discount_amount)}</td><td>${currentQuantity(invoice.discount_rate)}%</td><td>${attr(invoice.payment_type||'—')}</td><td><button type="button" class="current-invoice-toggle" data-current-action="toggle-invoice" data-invoice-id="${invoice.id}" aria-expanded="${expanded.has(Number(invoice.id))}">${expanded.has(Number(invoice.id))?'−':'+'}</button></td></tr>${expanded.has(Number(invoice.id))?`<tr class="current-invoice-detail"><td colspan="11"><table class="current-item-table"><thead><tr><th>Stok Kartı</th><th>Miktar</th><th>İskonto Oranı</th><th>Fiyat</th></tr></thead><tbody>${currentItemRows(invoice)}</tbody></table></td></tr>`:''}`).join(''):'<tr><td colspan="11" class="current-empty">Bu cariye ait fatura hareketi bulunmuyor.</td></tr>';
    const currentLedgerMarkup=(account,expanded)=>`<div class="current-card-toolbar" data-current-toolbar><button type="button" data-current-action="back">← Cari Listesi</button><button type="button" data-current-action="edit-current" data-current-id="${account.id}">✎ Cari Kartı</button><span class="current-message" role="status">${account.invoices?.length||0} fatura hareketi</span></div><div class="current-body"><div class="current-ledger-title">${attr(account.code)} — ${attr(account.title)}</div><div class="current-table-wrap"><table class="current-table current-ledger-table"><thead><tr><th>Tarih</th><th>Hareket</th><th>Fatura No</th><th>Miktar</th><th>T. Tutar</th><th>T. Tutar (KDV'li)</th><th>İskontosuz Fatura Tutarı</th><th>İskonto</th><th>Ort. İsk.</th><th>Ödeme Tipi</th><th></th></tr></thead><tbody>${currentLedgerRows(account,expanded)}</tbody></table></div></div>`;
    const currentAccountsMarkup=title=>`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="current-shell" data-current-view>${currentListMarkup()}</div>`;
    const setupCurrentAccountWindow=win=>{const view=win.querySelector('[data-current-view]'),expanded=new Set();let activeAccount=null;const message=text=>{const target=win.querySelector('.current-message');if(target)target.textContent=text;};const renderList=()=>{activeAccount=null;expanded.clear();view.innerHTML=currentListMarkup();};const load=async()=>{message('Cari kartlar yükleniyor…');try{const payload=await apiFetch(endpoints.currentAccounts);currentAccountRecords=payload.data;renderList();}catch(error){message(error.message);}};const openForm=item=>{view.innerHTML=currentFormMarkup(item||{});view.querySelector('[name="code"]')?.focus();};const openLedger=async id=>{view.innerHTML='<div class="current-card-toolbar"><button type="button" data-current-action="back">← Cari Listesi</button><span class="current-message">Fatura hareketleri yükleniyor…</span></div>';try{const payload=await apiFetch(`${endpoints.currentAccounts}/${id}`);activeAccount=payload.data;expanded.clear();view.innerHTML=currentLedgerMarkup(activeAccount,expanded);}catch(error){message(error.message);}};win.addEventListener('input',event=>{if(!event.target.matches('[data-current-search]'))return;const query=event.target.value.toLocaleLowerCase('tr-TR');win.querySelectorAll('tr[data-current-id]').forEach(row=>row.hidden=!row.dataset.search.includes(query));});win.addEventListener('submit',async event=>{const form=event.target.closest('[data-current-form]');if(!form)return;event.preventDefault();if(!form.reportValidity())return;const values=Object.fromEntries(new FormData(form));const id=form.dataset.currentId;message(id?'Cari kart güncelleniyor…':'Cari kart kaydediliyor…');try{await apiFetch(id?`${endpoints.currentAccounts}/${id}`:endpoints.currentAccounts,{method:id?'PUT':'POST',body:JSON.stringify(values)});await load();message(id?'Cari kart güncellendi.':'Cari kart eklendi.');}catch(error){message(error.message);}});win.addEventListener('click',async event=>{const button=event.target.closest('[data-current-action]');if(!button)return;const action=button.dataset.currentAction,row=button.closest('[data-current-id]'),id=Number(button.dataset.currentId||row?.dataset.currentId||0),item=currentAccountRecords.find(record=>Number(record.id)===id);if(action==='new')openForm({});if(action==='back')renderList();if(action==='edit'&&item)openForm(item);if(action==='edit-current'&&activeAccount)openForm(activeAccount);if(action==='movements'&&item)await openLedger(item.id);if(action==='delete'&&item&&confirm(`${item.short_name} cari kartı ve bağlı fatura hareketleri silinsin mi?`)){message('Cari kart siliniyor…');try{await apiFetch(`${endpoints.currentAccounts}/${item.id}`,{method:'DELETE'});await load();message('Cari kart silindi.');}catch(error){message(error.message);}}if(action==='toggle-invoice'&&activeAccount){const invoiceId=Number(button.dataset.invoiceId);expanded.has(invoiceId)?expanded.delete(invoiceId):expanded.add(invoiceId);view.innerHTML=currentLedgerMarkup(activeAccount,expanded);}});load();};
    const setupReservationWindow=win=>{const message=win.querySelector('.reservation-message'),showTab=key=>{win.querySelectorAll('[data-reservation-tab]').forEach(tab=>tab.classList.toggle('active',tab.dataset.reservationTab===key));win.querySelectorAll('[data-reservation-panel]').forEach(panel=>panel.hidden=panel.dataset.reservationPanel!==key);};const render=()=>{win.querySelector('[data-reservation-panel="calendar"]').innerHTML=reservationCalendarMarkup();win.querySelector('[data-reservation-panel="form"]').innerHTML='';win.querySelector('[data-reservation-panel="list"]').innerHTML=reservationListMarkup();win.querySelectorAll('[data-reservation-view]').forEach(button=>button.classList.toggle('active',button.dataset.reservationView===reservationData.view));};const load=async()=>{message.textContent='Rezervasyonlar yükleniyor…';try{const range=reservationRange(),view=reservationData.view,anchorDate=reservationData.anchorDate,payload=await apiFetch(`${endpoints.reservations}?start=${range.start}&end=${range.end}`);reservationData={...payload.data,view,anchorDate};render();message.textContent=`${reservationData.reservations.length} rezervasyon`;}catch(error){message.textContent=error.message;}};const openForm=(item={},date='',startTime='',endTime='')=>{win.querySelector('[data-reservation-entry-screen]')?.remove();win.insertAdjacentHTML('beforeend',reservationEntryScreen(item,date,startTime,endTime));};const save=async()=>{const form=win.querySelector('[data-reservation-form]');if(!form.reportValidity())return;const values=Object.fromEntries(new FormData(form));values.member_id=values.member_id?Number(values.member_id):null;values.employee_id=values.employee_id?Number(values.employee_id):null;const id=form.dataset.reservationRecordId;try{await apiFetch(id?`${endpoints.reservations}/${id}`:endpoints.reservations,{method:id?'PUT':'POST',body:JSON.stringify(values)});reservationData.anchorDate=values.reservation_date;await load();win.querySelector('[data-reservation-entry-screen]')?.remove();showTab('calendar');message.textContent='Rezervasyon kaydedildi.';}catch(error){const target=win.querySelector('[data-reservation-entry-message]')||message;target.textContent=error.message;}};win.querySelector('.reservation-tabs').onclick=event=>{const tab=event.target.closest('[data-reservation-tab]');if(tab)showTab(tab.dataset.reservationTab);};win.querySelector('[data-reservation-command="new"]').onclick=()=>openForm({},reservationData.anchorDate);win.querySelector('[data-reservation-command="save"]').onclick=()=>{if(win.querySelector('[data-reservation-entry-screen]'))save();else message.textContent='Kaydetmek için yeni bir rezervasyon açın.';};win.querySelector('[data-reservation-command="refresh"]').onclick=()=>load();win.addEventListener('change',event=>{if(event.target.matches('[data-reservation-form] [name="member_id"]')){const form=event.target.closest('form'),member=reservationData.members.find(item=>item.id===Number(event.target.value));if(member){form.elements.guest_name.value=member.name;form.elements.phone.value=formatPhone(member.phone);}}if(event.target.matches('[data-reservation-status]'))win.querySelector('[data-reservation-search]')?.dispatchEvent(new Event('input',{bubbles:true}));});win.addEventListener('input',event=>{if(!event.target.matches('[data-reservation-search]'))return;const q=event.target.value.toLocaleLowerCase('tr-TR'),status=win.querySelector('[data-reservation-status]').value;win.querySelectorAll('tr[data-reservation-row]').forEach(row=>row.hidden=(!row.dataset.search.includes(q))||(status&&row.dataset.status!==status));});win.addEventListener('contextmenu',event=>{if(event.target.closest('[data-reservation-id]'))return;const canvas=event.target.closest('.reservation-time-canvas'),day=event.target.closest('[data-reservation-date]');if(!canvas&&!day)return;event.preventDefault();if(canvas){const rect=canvas.getBoundingClientRect(),minutes=Math.min(21*60+30,Math.max(8*60,Math.round((8*60+((event.clientY-rect.top)/rect.height)*(14*60))/30)*30)),format=value=>`${String(Math.floor(value/60)).padStart(2,'0')}:${String(value%60).padStart(2,'0')}`;openForm({},canvas.dataset.reservationDate,format(minutes),format(minutes+30));}else openForm({},day.dataset.reservationDate);});win.addEventListener('click',async event=>{if(event.target.closest('[data-reservation-entry-close]')){win.querySelector('[data-reservation-entry-screen]')?.remove();showTab('calendar');return;}if(event.target.closest('[data-reservation-entry-save]')){await save();return;}const viewButton=event.target.closest('[data-reservation-view]');if(viewButton){reservationData.view=viewButton.dataset.reservationView;showTab('calendar');await load();return;}const navButton=event.target.closest('[data-reservation-nav]');if(navButton){const direction=Number(navButton.dataset.reservationNav),anchor=reservationParseDate(reservationData.anchorDate);if(reservationData.view==='day')reservationData.anchorDate=localIso(reservationAddDays(anchor,direction));else if(reservationData.view==='week')reservationData.anchorDate=localIso(reservationAddDays(anchor,direction*7));else reservationData.anchorDate=localIso(new Date(anchor.getFullYear(),anchor.getMonth()+direction,1));await load();return;}if(event.target.closest('[data-reservation-today]')){reservationData.anchorDate=localIso(new Date());await load();return;}const add=event.target.closest('[data-reservation-add]');if(add){openForm({},add.dataset.reservationAdd);return;}const eventButton=event.target.closest('[data-reservation-id]'),rowAction=event.target.closest('[data-reservation-action]');if(eventButton&&!rowAction){openForm(reservationData.reservations.find(item=>item.id===Number(eventButton.dataset.reservationId)));return;}if(rowAction){const row=rowAction.closest('tr'),item=reservationData.reservations.find(record=>record.id===Number(row.dataset.reservationRow));if(rowAction.dataset.reservationAction==='edit')openForm(item);if(rowAction.dataset.reservationAction==='delete'&&confirm('Bu rezervasyon silinsin mi?')){try{await apiFetch(`${endpoints.reservations}/${item.id}`,{method:'DELETE'});await load();showTab('list');message.textContent='Rezervasyon silindi.';}catch(error){message.textContent=error.message;}}}});load();};
    const auxMarkup = (id,title) => id==='listeler'?patientListMarkup(title):id==='staff'?employeeListMarkup(title):id==='shifts'?scheduleMarkup(title):id==='setup'?setupMarkup(title):id==='stock'?stockMarkup(title):id==='cash'?cashMarkup(title):id==='calendar'?reservationMarkup(title):id==='packages'?packagesMarkup(title):id==='current-accounts'?currentAccountsMarkup(title):id.startsWith('employee-')?employeeCardMarkup(title,employeeCardPayloads[id]):id.startsWith('member-')?memberCardMarkup(title,memberCardPayloads[id]):`<div class="aux-titlebar"><span>${title}</span><div class="window-controls"><button type="button" data-act="min">_</button><button type="button" data-act="max">□</button><button type="button" class="window-close" data-act="close">×</button></div></div><div class="aux-content"><div class="aux-toolbar"><button>▣ Listele</button><button>⊕ Yeni</button><button>▱ Kopyala</button><button>▧ Excel</button></div><table class="aux-grid"><thead><tr><th>Kayıt No</th><th>Açıklama</th><th>Durum</th></tr></thead><tbody><tr><td colspan="3">Kayıtları görüntülemek için “Listele” düğmesini kullanın.</td></tr></tbody></table></div>`;
    const openAuxWindow = (id,title) => {
      let win=document.querySelector(`.aux-window[data-window-id="${id}"]`);
      if(win){
        win.hidden=false;
        if(id==='stock')win.querySelector('[data-stock-tab="list"]')?.click();
        activateWindow(win);
        return;
      }
      win=document.createElement('section'); win.className='aux-window'; win.dataset.windowId=id; win.innerHTML=auxMarkup(id,title); document.body.appendChild(win);if(id==='listeler'||id==='staff'){win.style.width='900px';win.style.height='500px';}if(id==='stock'||id==='cash'||id==='calendar'||id==='current-accounts'){win.style.width='1080px';win.style.height='620px';}if(id==='packages'){win.style.width='900px';win.style.height='520px';}if(id==='shifts'){win.style.width='1120px';win.style.height='590px';}if(id==='setup'){win.style.width='840px';win.style.height='520px';}if(id.startsWith('member-')){win.style.width='900px';win.style.height='530px';}if(id.startsWith('employee-')){win.style.width='960px';win.style.height='620px';}
      const offset=document.querySelectorAll('.aux-window').length*24; Object.assign(win.style,{left:(230+offset)+'px',top:(70+offset)+'px'});if(id==='packages'){win.style.left='20px';win.style.top='50px';win.style.width=Math.max(760,Math.min(980,innerWidth-40))+'px';}activateWindow(win);
      const task=document.createElement('button'); task.type='button'; task.className='task-item'; task.dataset.windowId=id; task.textContent=title; document.querySelector('.tray').before(task); task.addEventListener('click',()=>toggleTaskWindow(win,task));
      let move=null; const bar=win.querySelector('.aux-titlebar');
      bar.addEventListener('pointerdown',e=>{if(e.target.closest('button')||win.classList.contains('maximized'))return;const r=win.getBoundingClientRect();move={x:e.clientX,y:e.clientY,l:r.left,t:r.top};bar.setPointerCapture(e.pointerId);activateWindow(win);});
      bar.addEventListener('pointermove',e=>{if(!move)return;win.style.left=Math.max(0,Math.min(innerWidth-win.offsetWidth,move.l+e.clientX-move.x))+'px';win.style.top=Math.max(0,Math.min(innerHeight-31-win.offsetHeight,move.t+e.clientY-move.y))+'px';});
      bar.addEventListener('pointerup',()=>move=null); bar.addEventListener('dblclick',e=>{if(!e.target.closest('button'))win.classList.toggle('maximized');});
      win.addEventListener('pointerdown',()=>activateWindow(win));
      win.querySelector('[data-act="min"]').onclick=()=>minimizeDesktopWindow(win,task);
      win.querySelector('[data-act="max"]').onclick=e=>{win.classList.toggle('maximized');e.currentTarget.textContent=win.classList.contains('maximized')?'❐':'□';};
      if(id==='calendar'){win.classList.add('maximized');win.querySelector('[data-act="max"]').textContent='❐';}
      const closeWindow=()=>{task.remove();win.remove();if(id.startsWith('member-'))delete memberCardPayloads[id];if(id.startsWith('employee-'))delete employeeCardPayloads[id];};
      win.querySelector('[data-act="close"]').onclick=closeWindow;
      if(id==='listeler'){const search=win.querySelector('[data-patient-search]'),status=win.querySelector('[data-patient-status]'),rows=[...win.querySelectorAll('.patient-table tbody tr')],count=win.querySelector('.list-count');const filterRows=()=>{const q=search.value.toLocaleLowerCase('tr-TR');let shown=0;rows.forEach(row=>{const visible=(!q||row.dataset.search.includes(q))&&(!status.value||row.dataset.status===status.value);row.hidden=!visible;if(visible)shown++;});count.textContent='Gösterilen '+shown;};search.addEventListener('input',filterRows);status.addEventListener('change',filterRows);rows.forEach(row=>row.addEventListener('click',()=>{rows.forEach(item=>item.classList.remove('selected'));row.classList.add('selected');openMemberCard(row);}));win.querySelector('[data-list-action="card"]').addEventListener('click',()=>{const selected=rows.find(row=>row.classList.contains('selected'));if(selected)openMemberCard(selected);else alert('Lütfen listeden bir üye seçin.');});win.querySelectorAll('[data-list-action="refresh"]').forEach(button=>button.addEventListener('click',()=>{search.value='';status.value='';filterRows();}));setupPatientColumns(win);}
      if(id==='setup')setupSetupWindow(win);
      if(id==='staff')setupEmployeeListWindow(win);
      if(id==='shifts')setupScheduleWindow(win);
      if(id==='stock')setupStockWindow(win);
      if(id==='cash')setupCashWindow(win);
      if(id==='packages')setupPackageWindow(win);
      if(id==='current-accounts')setupCurrentAccountWindow(win);
      if(id==='calendar'){reservationData.view='day';reservationData.anchorDate=localIso(new Date());setupReservationEntryInteractions(win);setupReservationMemberPhone(win);setupReservationWindow(win);setupReservationContextMenu(win);setupReservationDoubleClick(win);setupReservationDragDrop(win);setupReservationHoverDetails(win);}
      if(id.startsWith('employee-'))setupEmployeeCardWindow(win,id);
      if(id.startsWith('member-')){const form=win.querySelector('.member-card-form'),status=win.querySelector('.member-card-status');win.querySelectorAll('[data-member-tab]').forEach(tab=>tab.onclick=()=>{win.querySelectorAll('[data-member-tab]').forEach(item=>item.classList.toggle('active',item===tab));win.querySelectorAll('[data-member-panel]').forEach(panel=>panel.hidden=panel.dataset.memberPanel!==tab.dataset.memberTab);});form.addEventListener('submit',async event=>{event.preventDefault();status.textContent='Kaydediliyor…';try{await saveMemberCard(id,form);status.textContent='Üye bilgileri kaydedildi.';}catch(error){status.textContent=error.message;}});win.querySelector('[data-member-action="close"]').onclick=closeWindow;}
    };
    const openMemberCard=async row=>{try{const payload=await apiFetch(endpoints.members+'/'+row.dataset.memberId),member={...payload.data,_row:row},id='member-'+member.id;memberCardPayloads[id]=member;openAuxWindow(id,'Üye Kartı • '+member.memberNo+' • '+member.name);}catch(error){alert(error.message);}};
    const displayDate=value=>{if(!value)return'';const parts=value.split('-');return parts.length===3?`${parts[2]}.${parts[1]}.${parts[0]}`:value;};
    const saveMemberCard=async(id,form)=>{const member=memberCardPayloads[id],values=Object.fromEntries(new FormData(form));values.durationMonths=values.durationMonths===''?null:Number(values.durationMonths);values.contractAmount=values.contractAmount===''?null:Number(values.contractAmount);const payload=await apiFetch(endpoints.members+'/'+member.id,{method:'PUT',body:JSON.stringify(values)}),saved=payload.data,row=member._row;Object.assign(member,saved);const summary=memberRecords.find(item=>item.id===saved.id);if(summary)Object.assign(summary,{memberNo:saved.memberNo,name:saved.name,phone:saved.phone,membershipType:saved.membershipType,validThrough:saved.validThrough,status:saved.status});if(!row)return;saved._row=row;row.dataset.search=`${saved.memberNo} ${saved.name} ${saved.phone||''}`.toLocaleLowerCase('tr-TR');row.dataset.status=saved.status;row.cells[0].textContent=saved.memberNo;row.cells[1].textContent=saved.name;row.cells[2].textContent=formatPhone(saved.phone);row.cells[3].textContent=saved.membershipType;row.cells[4].textContent=displayDate(saved.validThrough);row.cells[5].innerHTML=`<span class="status-badge ${saved.status==='aktif'?'active':'passive'}">${saved.status==='aktif'?'Aktif':'Pasif'}</span>`;};
    const menuScreens={
      'Üyeler':['listeler','Üyeler'],
      'Rezervasyon':['calendar','Rezervasyon'],
      'Ön Kasa':['cash','Ön Kasa'],
      'Stok':['stock','Stok'],
      'Personel':['staff','Personel'],
      'Çalışma Programı':['shifts','Çalışma Programı'],
      'Raporlar':['reports','Raporlar'],
      'Kurulum':['setup','Kurulum'],
      'Paketler':['packages','Paketler'],
      'Cari Kartlar':['current-accounts','Cari Kartlar']
    };
    document.querySelectorAll('.start-menu a').forEach(link=>link.addEventListener('click',event=>{
      const screen=menuScreens[link.textContent.trim()]; if(!screen)return;
      event.preventDefault();
      if(screen[0]==='patient'){const task=document.getElementById('patient-task');appWindow.hidden=false;task.hidden=false;appWindow.classList.remove('minimized');requestAnimationFrame(centerWindow);activateWindow(appWindow);}
      else openAuxWindow(screen[0],screen[1]);
      setStartMenuOpen(false);
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
