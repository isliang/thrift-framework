<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-07
 * Time: 14:47
 */
namespace Isliang\Thrift\Framework;

use Thrift\Type\TType;
use Thrift\Type\TMessageType;
use Thrift\Exception\TApplicationException;

class ThriftServiceProcessor
{
    protected $handler_ = null;
    protected $module_name;

    public function __construct($handler, $module_name)
    {
        $this->handler_ = $handler;
        $this->module_name = $module_name;
    }

    public function process($input, $output)
    {
        $rseqid = 0;
        $fname = null;
        $mtype = 0;

        $input->readMessageBegin($fname, $mtype, $rseqid);
        $args_name = $this->module_name . '_' . $fname . '_args';
        $result_name = $this->module_name . '_' . $fname . '_result';

        if (!class_exists($args_name) || !class_exists($result_name)) {
            $input->skip(TType::STRUCT);
            $input->readMessageEnd();
            $x = new TApplicationException('Function '.$fname.' not implemented.', TApplicationException::UNKNOWN_METHOD);
            $output->writeMessageBegin($fname, TMessageType::EXCEPTION, $rseqid);
            $x->write($output);
            $output->writeMessageEnd();
            $output->getTransport()->flush();
            return;
        }
        //是否使用扩展
        $bin_accel = ($input instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_read_binary_after_message_begin');
        if ($bin_accel) {
            $args = thrift_protocol_read_binary_after_message_begin(
                $input,
                $args_name,
                $input->isStrictRead()
            );
        } else {
            $args = new $args_name();
            $args->read($input);
        }
        $input->readMessageEnd();
        $result = new $result_name();
        $param = $this->getParams($args);
        $result->success = call_user_func_array([$this->handler_, $fname], $param);
        $bin_accel = ($output instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_write_binary');
        if ($bin_accel) {
            thrift_protocol_write_binary(
                $output,
                $fname,
                TMessageType::REPLY,
                $result,
                $rseqid,
                $output->isStrictWrite()
            );
        } else {
            $output->writeMessageBegin($fname, TMessageType::REPLY, $rseqid);
            $result->write($output);
            $output->writeMessageEnd();
            $output->getTransport()->flush();
        }

        return true;
    }

    private function getParams($args)
    {
        $spec = $args::$_TSPEC;
        $ret = [];
        foreach ($spec as $key => $value) {
            $paramName = $value['var'];
            $ret[] = $args->$paramName;
        }
        return $ret;
    }
}
