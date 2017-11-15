<?php
namespace app\chat\model;

use think\Db as Db;
use think\Model;

class Friends extends Model {

	public static $users = 'users';

	/**
	 * 查询好友信息
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function getRowByUid($uid) {
		$rows = Db::table('friends')->alias('f')
			->join('users' . ' u', 'f.fid=u.uid', 'inner')
			->where('f.uid', $uid)
			->field('u.username,u.account,u.avatar,u.sex,u.age,f.group_id,f.uid,f.fid as id')
			->select();
		return $rows;
	}

	/**
	 * 查询用户分组
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function getGroupByUid($uid) {
		$rows = Db::table('group')
			->where('create_uid', $uid)
			->select();
		return $rows;
	}

	/**
	 * 根据手机号查询用户
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function getRowByPhone($phone) {
		$row = Db::table('users')->alias('u')
			->join('users_item' . ' m', 'u.uid=m.uid', 'inner')
			->where('account', $phone)->find();
		return $row;
	}
	/**
	 * 根据openid用户
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function getUsersByOpenid($openid) {
		$row = Db::table('users')
			->where('openid', $openid)
			->find();
		return $row;
	}

	public function getItemByUid($uid) {
		$row = Db::table('users_item')->where('uid', $uid)->find();
		return $row;
	}
	public function getUsersByCardId($cardid) {
		$row = Db::table('users_item')->where('cardid', $cardid)->find();
		return $row;
	}
	// 根据unionid用户
	public function getRowByUnionid($unionid) {
		$row = Db::table('users')->where('unionid', $unionid)->find();
		return $row;
	}

	/**
	 * 修改用户资料
	 */
	public function modifyUser($data, $where) {
		$row = Db::table('users')->where($where)->update($data);
		return $row;
	}

	// 根据时间查询积分明细
	public function getRowByTime($uid, $time) {
		$row = Db::table('score')->where('adddate', $time)->where('uid', $uid)->where('from', 101)->find();
		return $row;
	}

	// // 查询用户累积积分
	public function getRowsByScore($uid) {
		$total = Db::table('score')
			->where('state', 0)->where('uid', $uid)
			->sum('num');
		return $total;
	}

	// 查询用户佣金
	public function getRowsByRank($uid) {
		$total = Db::table('finance')
			->where('rank', '>', 1)->where('rank', '<', 10)->where('type', 1)->where('uid', $uid)
			->sum('money');
		return $total;
	}
	// // 查询用户补贴
	public function getRowsByType($uid) {
		$total = Db::table('finance')
			->where('rank', 1)->where('type', 1)->where('uid', $uid)
			->sum('money');
		return $total;
	}
	/**
	 * 增加积分明细
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function addRowByScore($row) {
		Db::name('score')->insert($row);
		$uid = Db::name('score')->getLastInsID();
		return $uid;
	}

	/**
	 * 添加用户
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function addRowByData($row) {
		Db::name('users')->insert($row);
		$uid = Db::name('users')->getLastInsID();
		return $uid;
	}
	/**
	 * 添加用户属性
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function addRowByArr($row) {
		Db::name('users_item')->insert($row);
		$uid = Db::name('users_item')->getLastInsID();
		return $uid;
	}
	/**
	 * 添加用户钱包
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function addRowByWallet($row) {
		Db::name('user_wallet')->insert($row);
		$uid = Db::name('user_wallet')->getLastInsID();
		return $uid;
	}
	/**
	 * 查询多条用户数据
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function getRowsByData($row) {
		$rows = Db::table('users')->where('openid', 'in', $row)->field('nickname,headimgurl,account,openid')->select();
		return $rows;
	}

	/**
	 * 更新积分
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function setRowByExtract($uid, $data) {
		$row = Db::table('extract')->where('uid', $uid)->update($data);
		return $row;
	}

	/**
	 * 更新积分
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function setRowByUid($uid, $data) {
		$row = Db::table('score')->where('uid', $uid)->update($data);
		return $row;
	}

	/**
	 * 更新用户unionid
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function setRowByOpenid($openid, $data) {
		$row = Db::table('users')->where('openid', $openid)->update($data);
		return $row;
	}
	/**
	 * 更新用户unionid
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function setRowByUnionId($unionid, $data) {
		$row = Db::table('users')->where('unionid', $unionid)->update($data);
		return $row;
	}
	/**
	 * 更新用户信息
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function setItemByUid($uid, $data) {
		$row = Db::table('users_item')->where('uid', $uid)->update($data);
		return $row;
	}
	/**
	 * 更新用户身份
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function setUsersByUid($uid, $data) {
		$row = Db::table('users')->where('uid', $uid)->update($data);
		return $row;
	}
	/**
	 * 更新用户绑定时间
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function setRowByTime($uid, $data) {
		$row = Db::table('relation')->where('uid', $uid)->update($data);
		return $row;
	}
	/**
	 * 解除绑定
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function delRowsByUid($uid) {
		Db::table('relation')->where('uid', $uid)->delete();
	}
	/**
	 * 更新用户积分(增加)
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function setRowByScore($openid, $score) {
		$row = Db::table('users')->where('openid', $openid)->setInc('score', $score);
		return $row;
	}
	/**
	 * 更新用户积分(减少)
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function setDecByScore($openid, $score) {
		$row = Db::table('users')->where('openid', $openid)->setDec('score', $score);
		return $row;
	}
	/**
	 * 添加关系
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function addRowByuid($data) {
		Db::name('relation')->insert($data);
		$insertid = Db::name('relation')->getLastInsID();
		return $insertid;
	}

	/**
	 * 更新用户佣金
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function setRowByMoney($uid, $money) {
		$row = Db::table('users')->where('uid', $uid)->setInc('bonus', $money);
		return $row;
	}

	/**
	 * 更新群管佣金
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function setRowByOuterMoney($uid, $outer_money) {
		$row = Db::table('users')->where('uid', $uid)->setInc('brokerage', $outer_money);
		return $row;
	}
	/**
	 * 更新用户积分
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function setRowByOscore($uid, $score) {
		$row = Db::table('users')->where('uid', $uid)->setInc('score', $score);
		return $row;
	}
	/**
	 * 根据用户iD获取用户数据
	 * @param int     $id     用户id
	 * @return array
	 */
	public function getUserById($uid) {

		$row = Db::table('users')->alias('u')
			->join('users_item' . ' m', 'u.uid=m.uid', 'inner')
			->where('u.uid', $uid)->find();
		return $row;
	}

	/**
	 * 查询店主所有粉丝
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function getRowsByPid($uid) {
		$rows = Db::table('relation')
			->alias('r')
			->join('users_item' . ' m', 'r.uid=m.uid', 'inner')
			->join('users' . ' u', 'u.uid=m.uid', 'inner')
			->where('parent_uid', $uid)
			->where('m.level', '<>', 0)
			->field('m.level, count(m.level) as count')
			->group('m.level')
			->select();
		return $rows;
	}

	/**
	 * 根据群uid查询关系
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function getRowByPid($uid) {
		$rows = Db::table('relation')->where('uid', $uid)->find();
		return $rows;
	}

	/**
	 * 根据uid查询用户的上一级是否是创业者
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function getRowByStore($uid) {
		$rows = Db::table('relation')
			->alias('r')
			->join('users_item u', 'r.parent_uid = u.uid', 'inner')
			->where(['r.uid' => $uid, 'u.identity' => 1])
			->field('u.*')
			->find();
		return $rows;
	}

	// 查询所有普通用户
	public function getCountByUid($uid, $type, $start, $limit) {
		$rows = Db::table('relation')
			->alias('r')
			->join('users_item' . ' m', 'r.uid=m.uid', 'inner')
			->join('users' . ' u', 'u.uid=m.uid', 'inner')
			->field('r.detatime,u.nickname,u.headimgurl,u.uid')
			->where('parent_uid', $uid)
			->where('m.identity', $type)
			->where('u.unionid', '<>', '0')
			->limit($start, $limit)
			->order('r.detatime', desc)
			->select();
		$total = Db::table('relation')
			->alias('r')
			->join('users_item' . ' m', 'r.uid=m.uid', 'inner')
			->join('users' . ' u', 'u.uid=m.uid', 'inner')
			->where('parent_uid', $uid)
			->where('m.identity', $type)
			->where('u.unionid', '<>', '0')
			->count();
		return array('total' => $total, 'rows' => $rows);
	}

	/**
	 * 删除冗余用户
	 * @param array    $where  		查询条件
	 * @return array
	 */
	public function removeRowByUid($uid) {
		Db::table('users')->where('uid', $uid)->delete();
		Db::table('users_item')->where('uid', $uid)->delete();
	}
	//提交反馈
	public function setWrite($data) {
		return Db::table('write')->insert($data);
	}
	public function getWalletByUid($uid) {
		$row = Db::table('user_wallet')
			->where('uid', $uid)->find();
		return $row;
	}
}