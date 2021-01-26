SELECT u.name, c.name, cm.joined_at
FROM community_members cm
         JOIN users u on u.id = cm.user_id
         JOIN communities c on c.id = cm.community_id
WHERE c.created_at >= TIMESTAMP (STR_TO_DATE('2013-01-01 00:00:00', '%Y-%m-%d %H:%i:%s'))
ORDER BY cm.joined_at DESC;