<a href="/moodle_statistic/" title="Startseite">zur&uuml;ck</a>
<?php $missingModule = $courseModule->findCourseWithoutModule(); ?>
<h1>Diskrepanzen im System</h1>

<h2>Kurse ohne Modul (&sum;: <?php echo count($missingModule); ?>)</h2>
<div style="max-height: 400px; overflow: auto;">
    <table>
        <thead>
            <tr>
                <th>Kurs ID</th>
                <th>Name</th>
                <th>Moodle-Link</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($missingModule) > 0) {
                foreach ($missingModule as $forum) {
                    ?>
                    <tr id="course-<?php echo $forum->id; ?>">
                        <td><?php echo $forum->id; ?></td>
                        <td><?php echo $forum->fullname; ?></td>

                        <td><a href="/course/view.php?id=<?php echo $forum->id; ?>" target="_blank">&Ouml;ffnen</a></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>

<?php $missingForum = $courseModule->findModuleCourseIdWithoutForum(); ?>
<h2>Modulkurse ohne Forum (&sum;: <?php echo count($missingForum); ?>)</h2>
<span>Suche in Modulen nach Kursen ohne Forum</span>
<div style="max-height: 400px; overflow: auto;">
    <table>
        <thead>
            <tr>
                <th>Kurs ID</th>
                <th>Moodle-Link</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($missingForum) > 0) {
                foreach ($missingForum as $forum) {
                    ?>
                    <tr id="course-<?php echo $forum->id; ?>">
                        <td><?php echo $forum->id; ?></td>

                        <td><a href="/course/view.php?id=<?php echo $forum->id; ?>" target="_blank">&Ouml;ffnen</a></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>

<?php $missingForum2 = $courseModule->findCourseDiffFromModuleForum(); ?>
<h2>Kurse ohne Forum (&sum;: <?php echo $missingForum2->num_rows; ?>)</h2>
<span>Suche in Kursen ohne Forum</span>
<div style="max-height: 400px; overflow: auto;">
    <table>
        <thead>
            <tr>
                <th>Kurs ID</th>
                <th>Name</th>
                <th>Moodle-Link</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($missingForum2->num_rows > 0) {
                while ($forum = $missingForum2->fetch_object()) {
                    ?>
                    <tr id="course-<?php echo $forum->id; ?>">
                        <td><?php echo $forum->id; ?></td>
                        <td><?php echo $forum->fullname; ?></td>
                        <td><a href="/course/view.php?id=<?php echo $forum->id; ?>" target="_blank">&Ouml;ffnen</a></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>

<style type="text/css">
    .attention_click {
        position: fixed;
        display: inline-block;
        top: 0px;
        left: 500px;
        width: 200px;
        margin: 0 auto;
        padding: 20px;
        color: #FF0000;
        background-color: #e7e7e7;
    }
</style>
<div class="attention_click">
    ACHTUNG: KEINE LINKS KLICKEN; sonst werden Foren angelegt !!!!
</div>
