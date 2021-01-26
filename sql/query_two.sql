SELECT c.id, c.name, p.name, COUNT(cm.id) as cm_count
FROM communities c
         JOIN community_members cm on c.id = cm.community_id
         JOIN community_member_permissions cmp on cm.id = cmp.member_id
         JOIN permissions p on p.id = cmp.permission_id
GROUP BY c.id, p.id
HAVING cm_count > 5
ORDER BY c.id DESC, cm_count
LIMIT 100;