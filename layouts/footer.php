</div> </div> <?php if ($session->isUserLoggedIn(true)): ?>
    <footer class="footer">
      <div class="container-fluid">
        <div class="row" style="display: flex; align-items: center;">
          <div class="col-sm-6">
            <span class="copyright">
              © <?php echo date("Y"); ?> 
              <strong>WMS <span class="brand-accent">Pro</span></strong>. 
              All rights reserved.
            </span>
          </div>

          <div class="col-sm-6 text-right">
            <span class="system-status">
              <span class="status-indicator"></span>
              System Operational
            </span>
          </div>
        </div>
      </div>
    </footer>
  <?php endif; ?>

  <style>
    /* =========================================
       HIGH-VISIBILITY MODERN FOOTER DESIGN
    ========================================= */
    .footer {
      background: #ffffff;
      padding: 25px 35px; 
      border-top: 2px solid #f1f5f9;
      font-size: 1rem; 
      font-weight: 500;
      color: #64748b;
      position: relative;
      z-index: 10;
    }

    .brand-accent {
      color: #3b82f6;
      font-weight: 800;
      font-size: 1.1rem; 
    }

    .system-status {
      font-weight: 700;
      color: #1e293b;
      display: inline-flex;
      align-items: center;
      background: #f8fafc;
      padding: 8px 16px;
      border-radius: 50px;
      border: 1px solid #e2e8f0;
    }

    .status-indicator {
      height: 12px;
      width: 12px;
      background: #10b981;
      border-radius: 50%;
      display: inline-block;
      margin-right: 10px;
      box-shadow: 0 0 12px rgba(16, 185, 129, 0.4);
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
      70% { transform: scale(1.2); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
      100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Adjust page spacing based on login status */
    .page {
      <?php if ($session->isUserLoggedIn(true)): ?>
        margin-bottom: 40px;
      <?php else: ?>
        margin-bottom: 0;
        padding-top: 0; /* Ensures login box stays centered */
      <?php endif; ?>
    }

    /* Datepicker Styling remains global for potential admin use */
    .datepicker {
      font-size: 1.1rem !important;
      padding: 20px !important;
      border-radius: 20px !important;
      box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
      border: 1px solid #e2e8f0 !important;
    }
  </style>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
  <script type="text/javascript" src="libs/js/functions.js"></script>

  <script>
    $(document).ready(function() {
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