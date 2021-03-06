BEGIN;

ALTER TABLE common_node ADD COLUMN custom_fields jsonb;

CREATE INDEX common_node_custom_fields_idx ON common_node USING gin (custom_fields);

UPDATE common_node SET node_controller = lower(node_controller);

UPDATE common_node SET node_controller = 'image_gallery' WHERE node_controller = 'picture' AND node_group='content';

ALTER TABLE common_node RENAME COLUMN teaser TO strapline;

CREATE INDEX education_survey_entry_survey_id_fkey ON education_survey_entry USING btree(survey_id);
CREATE INDEX education_survey_entry_answer_survey_entry_id_fkey ON education_survey_entry_answer USING btree(survey_entry_id);

ALTER TYPE acl_resource ADD VALUE 'build' AFTER 'permissions';

INSERT INTO "client_role_permission" ("role_id", "resource", "operation") VALUES (3, 'build', '_all_');

COMMIT;
