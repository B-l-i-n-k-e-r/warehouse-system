</div> </div> <?php if ($session->isUserLoggedIn(true)): ?>
    <footer class="footer">
      <div class="container-fluid">
        <div class="footer-flex">
          <div class="footer-left">
            <span class="copyright">
              © <?php echo date("Y"); ?> 
              <span class="brand-text">MoonLit <span class="brand-accent">WMS</span></span>. 
              <span class="reserved">All rights reserved.</span>
            </span>
          </div>

          <div class="footer-right">
            <div class="system-status-pill">
              <span class="status-indicator"></span>
              <span class="status-label">Core Engine Operational</span>
            </div>
          </div>
        </div>
      </div>
    </footer>
  <?php endif; ?>

  <style>
    /* --- MoonLit Footer Aesthetics --- */
    .footer {
      background: rgba(15, 23, 42, 0.8);
      backdrop-filter: blur(12px);
      padding: 20px 0;
      border-top: 1px solid rgba(56, 189, 248, 0.1);
      color: #94a3b8;
      margin-top: 50px;
      position: relative;
      z-index: 10;
    }

    .footer-flex {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 30px;
    }

    .brand-text {
      color: #fff;
      font-weight: 800;
      margin-left: 5px;
      letter-spacing: -0.3px;
    }

    .brand-accent {
      color: #38bdf8; /* Matched to your MoonLit Accent */
    }

    .reserved {
      margin-left: 10px;
      font-weight: 500;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      opacity: 0.5;
    }

    /* --- System Status Pill --- */
    .system-status-pill {
      display: inline-flex;
      align-items: center;
      background: rgba(16, 185, 129, 0.08);
      padding: 6px 16px;
      border-radius: 50px;
      border: 1px solid rgba(16, 185, 129, 0.2);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .system-status-pill:hover {
      background: rgba(16, 185, 129, 0.15);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .status-label {
      color: #10b981;
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .status-indicator {
      height: 8px;
      width: 8px;
      background: #10b981;
      border-radius: 50%;
      display: inline-block;
      margin-right: 10px;
      box-shadow: 0 0 12px rgba(16, 185, 129, 0.6);
      animation: status-pulse 2s infinite;
    }

    @keyframes status-pulse {
      0% { transform: scale(0.9); opacity: 0.8; }
      50% { transform: scale(1.2); opacity: 1; box-shadow: 0 0 18px rgba(16, 185, 129, 0.8); }
      100% { transform: scale(0.9); opacity: 0.8; }
    }

    /* --- UI Components Fixes --- */
    .datepicker {
      font-family: 'Plus Jakarta Sans', sans-serif !important;
      border-radius: 12px !important;
      padding: 10px !important;
      border: 1px solid rgba(56, 189, 248, 0.1) !important;
      background: #1e293b !important;
      color: #f8fafc !important;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
    }

    .datepicker table tr td.day:hover {
      background: rgba(56, 189, 248, 0.2) !important;
      border-radius: 8px;
    }

    .datepicker table tr td.active {
      background: #38bdf8 !important;
      color: #0f172a !important;
      font-weight: 800;
      border-radius: 8px !important;
    }
    
    /* Global Content Fitting Utility */
    .table-fit {
      width: auto !important;
      white-space: nowrap !important;
    }
  </style>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
  <script type="text/javascript" src="libs/js/functions.js"></script>

  <script>
    $(document).ready(function() {
      // Modern Datepicker Init
      $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true
      });
    });
  </script>

</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>