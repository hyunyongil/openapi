CREATE TABLE dmmapi_admin (
  id smallint unsigned not null auto_increment comment 'id',
  username varchar(30) not null comment '사용자아이디',
  password char(40) not null comment '비밀번호',
  is_use tinyint unsigned not null default '1' comment '사용여부, 1：사용함，0：사용안함',
  privilege tinyint unsigned not null default '1' comment '권한, 1: 모든권한, 2: 개발자(api작성, api읽기), 3: 게스트(api읽기)',
  addtime int unsigned not null comment '가입시간',
  primary key (id),
  key (username),
  key (is_use)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='관리자';
INSERT INTO `dmmapi_admin` VALUES (1,'admin','7c4a8d09ca3762af61e59520943dc26494f8941b',1,1,1643182304);


CREATE TABLE dmmapi_admin_project (
  id mediumint unsigned not null auto_increment comment 'id',
  admin_id smallint unsigned not null comment '관리자id',
  project_id mediumint unsigned not null comment '프로젝트id',
  primary key (id),
  key (admin_id),
  key (project_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='관리자-프로젝트';


CREATE TABLE dmmapi_project (
  id mediumint unsigned not null auto_increment comment 'id',
  pj_name varchar(100) not null comment '프로젝트명',
  host varchar(100) not null default '' comment 'host',
  description varchar(800) not null default '' comment '상세설명',
  is_use tinyint unsigned not null default '1' comment '사용여부, 1：사용함，0：사용안함',
  addtime int unsigned not null comment '생성시간',
  primary key (id),
  key (is_use)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='프로젝트';


CREATE TABLE dmmapi_module (
  id mediumint unsigned not null auto_increment comment 'id',
  project_id mediumint unsigned not null comment '프로젝트id',
  mo_name varchar(60) not null comment '모듈명',
  primary key (id),
  key (project_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='프로젝트 모듈';


CREATE TABLE dmmapi_api (
  id int unsigned not null auto_increment comment 'id',
  project_id mediumint unsigned not null comment '프로젝트id',
  module_id mediumint unsigned not null comment '모듈id',
  group_code char(13) not null comment 'api그룹',
  version decimal(4, 1) unsigned not null default '1.0' comment 'api버전',
  status tinyint unsigned not null default '0' comment 'api 상태, 0: testing, 1: publish',
  api_name varchar(60) not null comment 'api명',
  api_desc varchar(800) not null default '' comment 'api 상세설명',
  api_path varchar(200) not null default '' comment 'api path',
  api_method char(10) not null default 'GET' comment 'api method',
  with_token tinyint unsigned not null default '1' comment 'token 사용여부',
  request_demo text not null comment 'request demo',
  response_demo text not null comment 'response demo',
  sort int unsigned not null default '9' comment '순서배열',
  create_admin smallint unsigned not null comment 'create admin',
  create_time int unsigned not null comment 'create time',
  last_edit_admin smallint unsigned not null default '0' comment 'last edit admin',
  last_edit_time int unsigned not null default '0' comment 'last edit time',
  memo varchar(800) not null default '' comment '메모',
  primary key (id),
  key (project_id),
  key (module_id),
  key (group_code),
  key (status),
  key (api_name),
  key (sort)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='api';


CREATE TABLE dmmapi_request_param (
  id int unsigned not null auto_increment comment 'id',
  parent_id int unsigned not null default '0' comment '상위파라미터 id',
  org_id varchar(30) not null default '' comment 'api 추가 페이지에서 생성한 id, parent_id 생성시 오류 기록함',
  api_id int unsigned not null comment 'api id',
  req_key varchar(100) not null comment 'request key',
  req_type char(16) not null default '' comment 'request type',
  req_mode tinyint unsigned not null default '1' comment '필수여부, 1: 필수, 0: 비필수',
  req_description varchar(800) not null default '' comment '상세설명',
  req_sort int unsigned not null default '9' comment '순서배열',
  primary key (id),
  key (parent_id),
  key (org_id),
  key (api_id),
  key (req_sort)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='api request parameter';


CREATE TABLE dmmapi_response_param (
  id int unsigned not null auto_increment comment 'id',
  parent_id int unsigned not null default '0' comment '상위파라미터 id',
  org_id varchar(30) not null default '' comment 'api 추가 페이지에서 생성한 id, parent_id 생성시 오류 기록함',
  api_id int unsigned not null comment 'api id',
  res_key varchar(100) not null comment 'request key',
  res_type char(16) not null default '' comment 'request type',
  res_description varchar(800) not null default '' comment '상세설명',
  res_sort int unsigned not null default '9' comment '순서배열',
  primary key (id),
  key (parent_id),
  key (org_id),
  key (api_id),
  key (res_sort)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='api response parameter';


CREATE TABLE dmmapi_error (
  id int unsigned not null auto_increment comment 'id',
  project_id mediumint unsigned not null default '0' comment '프로젝트id',
  api_id int unsigned not null default '0' comment 'api id',
  state smallint unsigned not null default '200' comment 'error state',
  code char(10) not null default '' comment 'error code',
  message varchar(800) not null default '' comment 'error message',
  description text not null comment 'error description',
  sort mediumint unsigned not null default '9' comment '순서배열',
  primary key (id),
  key (project_id),
  key (api_id),
  key (sort)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='api error info';


CREATE TABLE dmmapi_log (
  id int unsigned not null auto_increment comment 'id',
  keyword char(10) not null default '' comment 'keyword, admin/project/api/error',
  method char(6) not null default '' comment 'method, insert/update/delete',
  relation_id int unsigned not null default '0' comment 'admin/project/api/error table id',
  message varchar(800) not null default '' comment 'message',
  admin_id smallint unsigned not null comment 'admin id',
  addtime int unsigned not null comment 'addtime',
  primary key (id),
  key (keyword),
  key (method),
  key (admin_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='api log';