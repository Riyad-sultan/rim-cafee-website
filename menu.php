<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: admin/login.php?msg=please_login");
    exit();
}

include 'includes/db_connect.php';
include "includes/header.php";

$user_id=$_SESSION['user_id'];
$user_query=mysqli_query($conn,"SELECT balance FROM user WHERE id='$user_id'");
$user_data =mysqli_fetch_assoc($user_query);
$current_balance=$user_data['balance'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Menu  | Rim Caffe</title>
    <link real="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .selected-highlight{
            border: 2px solid var(--gold) !important;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(212,175,55,0.3) !important;
        }
        .low-balance-alert{
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(231,76,60,0.9);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            z-index: 5;
        }

        @media(max-width: 768px){
            .btn-smart-mobile{
                width: 90% !important;
                padding : 12px 20px !important;
                font-size: 1rem !important;
            }
            .section-title{font-size:2.2rem !important;}
        }
        
    </style>
</head>
<body class="bg-dark text-white">
    <div class="conatainer py-5">
        <?php
        if(isset($_GET['error']) && $ $_GET['error'] == 'insufficient_funds'):
        ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger mb-4">
            <i class="fas fa-exclamation-triangle"></i>Insufficient Funds! Please recharge your wallet.
        </div>
        <?php endif;?>
        <h2 class="section-title mb-5 text-center" style="font-family: 'Playfair Display'; color: var(--gold); font-size: 3rem;">Our Menu></h2>
        <form action="order.php" method="GET">
            <div class="row g-4">
                <?php
                $query="SELECT * FROM menu WHERE is available = 1";
                $result= mysqli_query($conn,$query)

                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        $img=!empty($row['image']) ? 'assets/images/items'.$row['image'] : 'assets/images/placeholder.jpg';
                        $can_afford = ($current_balance >= $row['price']);

                    }
                }
                ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="menu-card glass-card h-100 position-relative border-0" id="item_card_<?php echo $row['id'];?>" styel="overflow: hidden;">
                        <?php if(!$can_afford):?>
                            <div class="low-balance-alert"><i class="fas fa-wallet"></i> Low Funds</div>
                            <?php endif; ?>

                                <div class="menu-img" style="
                                    background-image: url('<?php echo $img; ?>'); 
                                    height: 250px; 
                                    background-size: cover; 
                                    background-position: center; 
                                    width: 100%;
                                    border-bottom: 1px solid rgba(255,255,255,0.1);
                                "></div>
                                                             <div class="menu-info p-3">
                                    <h3 style="font-family: 'Playfair Display'; color: var(--gold); font-size: 1.4rem;"><?php echo htmlspecialchars($row['name']); ?></h3>
                                    <p class="text-secondary small mb-3" style="min-height: 40px;"><?php echo htmlspecialchars($row['description']); ?></p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="price fw-bold text-warning fs-5">$<?php echo number_format($row['price'], 2); ?></span>
                                        <span class="badge bg-secondary opacity-75 fw-light" style="font-size: 0.65rem; letter-spacing: 1px;"><?php echo htmlspecialchars($row['category']); ?></span>
                                    </div>

                                    <div class="d-flex align-items-center gap-2 p-2 rounded-3 border <?php echo $can_afford ? 'border-warning border-opacity-25' : 'border-secondary border-opacity-25'; ?>" 
                                         style="background: <?php echo $can_afford ? 'rgba(212, 175, 55, 0.05)' : 'rgba(255,255,255,0.02)'; ?>;">
                                        
                                        <input type="checkbox" name="item_ids[]" value="<?php echo $row['id']; ?>" 
                                               id="item_check_<?php echo $row['id']; ?>" 
                                               class="form-check-input m-0 shadow-none"
                                               style="width: 20px; height: 20px; accent-color: var(--gold);"
                                               <?php echo !$can_afford ? 'disabled' : ''; ?>
                                               onchange="highlightCard(this, 'item_card_<?php echo $row['id']; ?>')">
                                        
                                        <label for="item_check_<?php echo $row['id']; ?>" class="m-0 small fw-bold <?php echo $can_afford ? 'text-warning' : 'text-muted'; ?>" style="cursor: pointer; flex-grow: 1;">
                                            <?php echo $can_afford ? 'Select Item' : 'Insufficient Balance'; ?>
                                        </label>
     </div>
                                </div>
                            </div>
                        </div>
                        <?php
                ?>
            </div>

            <div class="text-center py-5">
                <div class="fixed-bottom pb-4 d-flex justify-content-center pointer-events-none" style="z-index: 1000;">
                    <button type="submit" class="btn-smart btn-smart-mobile" style="pointer-events: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.8); padding: 15px 50px; border-radius: 50px;">
                        Confirm My Selection <i class="fas fa-chevron-right ms-2 small"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
    function highlightCard(checkbox, cardId) {
        const card = document.getElementById(cardId);
        if(checkbox.checked) {
            card.classList.add('selected-highlight');
        } else {
            card.classList.remove('selected-highlight');
        }
    }

    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const selectedId = urlParams.get('selected_id');

        if (selectedId) {
            const checkbox = document.getElementById('item_check_' + selectedId);
            const card = document.getElementById('item_card_' + selectedId);
            
            if (checkbox && card) {
                checkbox.checked = true;
                card.classList.add('selected-highlight');
                setTimeout(() => {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
            }
        }
    }
    </script>

    <?php include "includes/footer.php"; ?>
</body>
</html>