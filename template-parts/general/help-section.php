<?php
?>
<div class="row">
    <div class="col-md-12">
        <div class="help-header">
            <div class="help-header-aside">
                <h2 class="help-header-title"><?php echo $args['title']; ?></h2>
                <h3 class="help-header-subtitle"><?php echo $args['text']; ?></h3>
            </div>
            <a href="<?php echo $args['url']; ?>" class="help-header-btn btn btn-outline-primary btn-with-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M3.5 3.46884V12.5313C3.5 13.0763 4.03125 13.4226 4.46875 13.1613L12.2156 8.52478C12.5944 8.29822 12.5944 7.70197 12.2156 7.47541L4.46875 2.83884C4.03125 2.57759 3.5 2.92384 3.5 3.46884Z" stroke="white" stroke-miterlimit="10" />
                </svg>
                <?php _e('Ajuda', 'pu'); ?></a>
        </div>
    </div>
</div>