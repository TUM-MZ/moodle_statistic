<?php $missingForum = $courseModule->findForumWithoutCourse(); ?>
<h1>Diskrepanzen im System</h1>

<h2>Kurse ohne Forum (&sum;: <?php echo count($missingCourse); ?>)</h2>
<div style="max-height: 400px;">
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
            if (count($missingForum) > 0) {
                foreach ($missingForum as $forum) {
                    ?>
                    <tr id="forum-<?php echo $forum->id; ?>">
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