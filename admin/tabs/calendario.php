<?php
function bia_calendario() {
    date_default_timezone_set('America/Sao_Paulo');

    echo '<link rel="stylesheet" href="' . BIA_URL . 'assets/css/calendario.css?v=' . time() . '" type="text/css" media="all" />';


    // Captura de mês/ano via GET
    $current_month = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
    $current_year = isset($_GET['ano']) ? (int)$_GET['ano'] : (int)date('Y');

    if ($current_month < 1) {
        $current_month = 12;
        $current_year--;
    } elseif ($current_month > 12) {
        $current_month = 1;
        $current_year++;
    }

    $days_in_month = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);

    // Nome do mês formatado
    $formatter = new IntlDateFormatter(
        'pt_BR',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'America/Sao_Paulo',
        IntlDateFormatter::GREGORIAN,
        'MMMM yyyy'
    );
    $month_name = $formatter->format(new DateTime("$current_year-$current_month-01"));

    // Navegação
    $prev_month = $current_month - 1;
    $next_month = $current_month + 1;
    $prev_year = $current_year;
    $next_year = $current_year;

    if ($prev_month < 1) {
        $prev_month = 12;
        $prev_year--;
    }

    if ($next_month > 12) {
        $next_month = 1;
        $next_year++;
    }

    $prev_link = add_query_arg(['mes' => $prev_month, 'ano' => $prev_year]);
    $next_link = add_query_arg(['mes' => $next_month, 'ano' => $next_year]);

    // Posts do mês
    $args = [
        'post_type'   => 'post',
        'post_status' => ['publish', 'future'],
        'date_query'  => [
            [
                'year'  => $current_year,
                'month' => $current_month,
            ],
        ],
        'posts_per_page' => -1,
    ];
    $posts = get_posts($args);

    $posts_by_day = [];
    foreach ($posts as $post) {
        $day = date('j', strtotime($post->post_date));
        $posts_by_day[$day][] = $post;
    }
    ?>

    <div class="wrap" style="padding: 0; height: 100vh;">
        <h2 style="text-align: center; margin-bottom: 20px;">
            <a href="<?php echo esc_url($prev_link); ?>" class="button" style="float:left;">← Mês Anterior</a>
            Calendário de Posts - <?php echo ucfirst($month_name); ?>
            <a href="<?php echo esc_url($next_link); ?>" class="button" style="float:right;">Próximo Mês →</a>
        </h2>
        <div class="calendar-container">
            <table class="calendar">
                <thead>
                    <tr>
                        <th>Dom</th>
                        <th>Seg</th>
                        <th>Ter</th>
                        <th>Qua</th>
                        <th>Qui</th>
                        <th>Sex</th>
                        <th>Sáb</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $day_of_week = date('w', strtotime("$current_year-$current_month-01"));
                    $current_day = 1;

                    echo '<tr>';
                    for ($i = 0; $i < $day_of_week; $i++) {
                        echo '<td class="empty"></td>';
                    }

                    while ($current_day <= $days_in_month) {
                        if ($day_of_week == 7) {
                            $day_of_week = 0;
                            echo '</tr><tr>';
                        }

                        echo '<td>';
                        echo "<strong>$current_day</strong>";

                        if (isset($posts_by_day[$current_day])) {
                            $post_count = count($posts_by_day[$current_day]);
                            $display_count = 0;

                            foreach ($posts_by_day[$current_day] as $post) {
                                if ($display_count < 3) {
                                    $status_class = ($post->post_status == 'publish') ? 'published' : 'scheduled';
                                    echo '<br><a href="' . get_permalink($post) . '" class="' . $status_class . '">' . esc_html($post->post_title) . '</a>';
                                }
                                $display_count++;
                            }

                            if ($post_count > 3) {
                                echo '<br><a href="#" class="view-more" data-day="' . $current_day . '">Ver Mais</a>';
                                echo '<div class="more-posts" id="more-posts-' . $current_day . '" style="display:none;">';
                                foreach ($posts_by_day[$current_day] as $post) {
                                    $status_class = ($post->post_status == 'publish') ? 'published' : 'scheduled';
                                    echo '<br><a href="' . get_permalink($post) . '" class="' . $status_class . '">' . esc_html($post->post_title) . '</a>';
                                }
                                echo '</div>';
                            }
                        }

                        echo '</td>';
                        $current_day++;
                        $day_of_week++;
                    }

                    while ($day_of_week < 7) {
                        echo '<td class="empty"></td>';
                        $day_of_week++;
                    }

                    echo '</tr>';
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
     .calendar-container {
    width: 100%;
    height: calc(100vh - 100px); /* aumenta a margem inferior */
    overflow-y: auto;
    overflow-x: hidden;
}

        .calendar {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .calendar th, .calendar td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            vertical-align: top;
        }

        .calendar th {
            background-color: #f4f4f4;
        }

        .calendar td {
            background-color: #fff;
        }

        .calendar td.empty {
            background-color: #f9f9f9;
        }

        .published {
            color: green;
        }

        .scheduled {
            color: orange;
        }

        .view-more {
            font-size: 11px;
            color: #8c52ff;
            cursor: pointer;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.view-more').forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    var day = this.getAttribute('data-day');
                    var morePosts = document.getElementById('more-posts-' + day);
                    if (morePosts.style.display === 'none') {
                        morePosts.style.display = 'block';
                        this.textContent = 'Ver Menos';
                    } else {
                        morePosts.style.display = 'none';
                        this.textContent = 'Ver Mais';
                    }
                });
            });
        });
    </script>
<?php
}