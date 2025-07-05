document.addEventListener('DOMContentLoaded', function() {
    // ... existing code ...

    function toggleSidebar() {
        if (window.innerWidth <= 768) {
            if (document.getElementById('sidebar').style.transform === 'translateY(0px)') {
                document.getElementById('sidebar').style.transform = 'translateY(-100%)';
            } else {
                document.getElementById('sidebar').style.transform = 'translateY(0)';
            }
        } else {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');
        }
    }

    if (document.getElementById('toggleSidebar')) {
        document.getElementById('toggleSidebar').addEventListener('click', toggleSidebar);
    }

    if (document.getElementById('mobileToggle')) {
        document.getElementById('mobileToggle').addEventListener('click', toggleSidebar);
    }

    document.querySelectorAll('.sidebar-menu li').forEach(item => {
        item.addEventListener('click', function() {
            const contentId = this.getAttribute('data-content');
            
            document.querySelectorAll('.sidebar-menu li').forEach(mi => mi.classList.remove('active'));
            document.querySelectorAll('.content-section').forEach(cs => cs.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(contentId).classList.add('active');

            if (window.innerWidth <= 768) {
                document.getElementById('sidebar').style.transform = 'translateY(-100%)';
            }
        });
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            document.getElementById('sidebar').classList.remove('expanded');
            document.getElementById('sidebar').classList.remove('collapsed');
            document.querySelector('.main-content').classList.remove('expanded');
            document.getElementById('sidebar').style.transform = '';
        } else {
            document.getElementById('sidebar').style.transform = 'translateY(-100%)';
        }
    });

    if (window.innerWidth <= 768) {
        document.getElementById('sidebar').style.transform = 'translateY(-100%)';
    }

    console.log('Dashboard script loaded');
    console.log('Mobile toggle button:', document.getElementById('mobileToggle'));

    // ... existing code ...

    // Get the modal
    var modal = document.getElementById("addSupplierModal");

    // Get the button that opens the modal
    var btn = document.getElementById("addSupplierBtn");
    
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    
    // When the user clicks the button, open the modal 
    btn.onclick = function() {
      modal.style.display = "block";
    }
    
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }
    
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
    
    // Handle form submission
    document.getElementById("addSupplierForm").onsubmit = function(e) {
      // Here you would typically send the form data to the server
      // For now, we'll just log it to the console
      console.log("Supplier Name:", document.getElementById("supplierName").value);
      console.log("Supplier Contact:", document.getElementById("supplierContact").value);
      console.log("Supplier Email:", document.getElementById("supplierEmail").value);
      
      // Close the modal after submission
      modal.style.display = "none";
      
      // Clear the form
      return true;
    }
});